<?php
declare(strict_types=1);

namespace App\services\Manticore;


use App\entities\Question\Question;
use App\entities\Question\QuestionRepository;
use App\forms\Manticore\IndexCreateForm;
use App\forms\Manticore\IndexDeleteForm;
use App\models\QuestionStats;
use App\repositories\Question\QuestionStatsRepository;
use DateTimeImmutable;
use DomainException;
use JsonException;
use Manticoresearch\Client;
use Manticoresearch\Index;
use Manticoresearch\Query\BoolQuery;
use Manticoresearch\Query\In;

/**
 * Class IndexService
 * @packaage App\services\Manticore
 * @author Aleksey Gusev <audetv@gmail.com>
 */
class IndexService
{
    private Client $client;
    private QuestionStatsRepository $questionStatsRepository;
    private QuestionRepository $questionRepository;

    public function __construct(
        Client $client,
        QuestionStatsRepository $questionStatsRepository,
        QuestionRepository $questionRepository
    ) {
        $this->client = $client;
        $this->questionStatsRepository = $questionStatsRepository;
        $this->questionRepository = $questionRepository;
    }

    public function create(IndexCreateForm $form): void
    {
        $name = $form->name;
        if ($name === '') {
            $name = 'questions';
        }
        $index = new Index($this->client);
        $index->setName($name);
        $index->drop(true);

        $index->create(
            [
                'username' => ['type' => 'text'],
                'role' => ['type' => 'text'],
                'text' => ['type' => 'text'],
                'datetime' => ['type' => 'text'],
                'data_id' => ['type' => 'integer'],
                'parent_id' => ['type' => 'integer'],
                'type' => ['type' => 'integer'],
                'position' => ['type' => 'integer']
            ],
            [
                'morphology' => 'stem_ru'
            ]
        );
    }

    public function delete(IndexDeleteForm $form): void
    {
        $params = [
            'index' => $form->name,
            'body' => ['silent' => true]
        ];

        $this->client->indices()->drop($params);
    }

    public function index(): void
    {
        $params = ['index' => 'questions'];
        $this->client->indices()->truncate($params);

        $index = new Index($this->client);
        $index->setName('questions');

        $files = $this->readDir();
        foreach ($files as $file) {
            $doc = $this->readFile($file);
            $this->addQuestion($doc, $index);
        }
    }

    private function readFile(string $file): bool|string
    {
        return file_get_contents(__DIR__ . "/../../../data/$file");
    }

    private function readDir(): array
    {
        $arrFiles = array();

        $handle = opendir(__DIR__ . '/../../../data');
        if ($handle) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != ".." && $entry != ".gitignore") {
                    $arrFiles[] = $entry;
                }
            }
        }
        closedir($handle);

        return $arrFiles;
    }

    /**
     * @param int $id
     * @return void
     */
    public function updateQuestionComments(int $id): void
    {
        $stats = null;
        $index = $this->client->index('questions');

        try {
            // Читаем из таблицы question_stats строку по id вопроса, чтобы получить кол-во комментариев
            // Если записи ещё в базе нет, значит читаем все записи из текущего индекса в секции catch
            $stats = $this->questionStatsRepository->getByQuestionId($id);
            $total = $stats->comments_count;
        } catch (DomainException $e) {

            // получаем кол-во комментариев через фильтр parent_id и data_id равны id вопроса
            $query = new BoolQuery();
            $query->should(new In('parent_id', [$id]));
            $query->should(new In('data_id', [$id]));

            $search = $index->search($query);

            // включаем сортировку, если убрать сортировку, то кол-во результатов query будет 20
            $search->sort('type', 'asc');
            $search->sort('position', 'asc');

            // получаем кол-во комментариев в вопросе, комментарии пронумерованы через position
            $total = $search->get()->getTotal() - 1;
        }

        // читаем обновленный файл, который обновляет по cron parser
        $doc = $this->readFile(\Yii::$app->params['questions']['current']['file']);

        try {
            $topic = json_decode($doc, false, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            echo $file . ": " . $e->getMessage() . "\n";
        }

        if (count($topic->comments) > $total) {
            // перебираем комментарии в массиве, если ключ станет больше,
            // чем полученное ранее в query значение total, то добавляем эти документы в индекс.
            foreach ($topic->comments as $key => $comment) {
                $comment->position = $key + 1;
                if (($key + 1) > $total) {
                    $index->addDocument($comment);
                }
            }
        }

        // Если запись в таблице со статистикой по вопросу существовала, то обновляем данные
        // В противном случае создаем объект статистики и записываем в базу,
        // чтобы в последующим при обновлении не дёргать поиск по индексу для получения кол-ва комментариев
        if ($stats) {
            $stats->changeCommentsCount(count($topic->comments), new DateTimeImmutable());
        } else {
            $stats = QuestionStats::create($id, count($topic->comments), new DateTimeImmutable());
        }
        $this->questionStatsRepository->save($stats);
    }

    public function updateQuestion(mixed $id): void
    {
        $this->deleteQuestion($id);

        $index = $this->client->index('questions');

        $doc = $this->readFile(\Yii::$app->params['questions']['current']['file']);
        $this->addQuestion($doc, $index);

    }

    public function deleteQuestion($id): void
    {
        $index = $this->client->index('questions');
        $query = new BoolQuery();
        $query->should(new In('parent_id', [$id]));
        $query->should(new In('data_id', [$id]));

        $index->deleteDocuments($query);
    }

    private function addQuestion($doc, Index $index): void
    {
        try {
            $topic = json_decode($doc, false, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            echo $file . ": " . $e->getMessage() . "\n";
        }

        $index->addDocument($topic->question);

        // Записываем ИД вопроса
        $question_id = (int)$topic->question->data_id;

        if ($topic->linked_question) {
            foreach ($topic->linked_question as $key => $linkedQuestion) {
                $linkedQuestion->position = $key + 1;
                $index->addDocument($linkedQuestion);
            }
        }

        foreach ($topic->comments as $key => $comment) {
            $comment->position = $key + 1;
            $index->addDocument($comment);

            try {
                $question = $this->questionRepository->getByDataId((int)$comment->data_id);
            } catch (DomainException $e) {
                $question = Question::create(
                    (int)$comment->data_id,
                    (int)$comment->parent_id,
                    (int)$comment->position,
                    $comment->username,
                    $comment->role,
                    trim($comment->text),
                    DateTimeImmutable::createFromFormat("H:i d.m.Y", $comment->datetime)
                );

                $this->questionRepository->save($question);
            }
        }

        try {
            $question = $this->questionRepository->getByDataId((int)$topic->question->data_id);
        } catch (DomainException $e) {
            $question = Question::create(
                (int)$topic->question->data_id,
                (int)$topic->question->parent_id,
                0,
                $topic->question->username,
                $topic->question->role,
                $topic->question->text,
                DateTimeImmutable::createFromFormat("H:i d.m.Y", $topic->question->datetime)
            );

            $this->questionRepository->save($question);
        }

        // Если запись в таблице со статистикой по вопросу существовала, то обновляем данные
        // В противном случае создаем объект статистики и записываем в базу,
        // чтобы в последующим при обновлении не дёргать поиск по индексу для получения кол-ва комментариев
        try {
            $stats = $this->questionStatsRepository->getByQuestionId($question_id);
            $stats->changeCommentsCount(count($topic->comments), new DateTimeImmutable());
        } catch (DomainException $e) {
            $stats = QuestionStats::create($question_id, count($topic->comments), new DateTimeImmutable());
        }
        $this->questionStatsRepository->save($stats);
    }
}
