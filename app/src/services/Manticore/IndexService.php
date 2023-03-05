<?php
declare(strict_types=1);

namespace App\services\Manticore;


use App\Question\Entity\Question\Question;
use App\entities\Question\QuestionRepository;
use App\forms\Manticore\IndexCreateForm;
use App\forms\Manticore\IndexDeleteForm;
use App\models\QuestionStats;
use App\Question\Entity\Question\Comment;
use App\Question\Entity\Question\CommentRepository;
use App\Question\Entity\Question\Id;
use App\repositories\Question\QuestionStatsRepository;
use DateTimeImmutable;
use DomainException;
use Exception;
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
    private CommentRepository $commentRepository;

    public function __construct(
        Client $client,
        QuestionStatsRepository $questionStatsRepository,
        QuestionRepository $questionRepository,
        CommentRepository $commentRepository,
    ) {
        $this->client = $client;
        $this->questionStatsRepository = $questionStatsRepository;
        $this->questionRepository = $questionRepository;
        $this->commentRepository = $commentRepository;
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
                'datetime' => ['type' => 'timestamp'],
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

    /**
     * @throws Exception
     */
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
     * @throws Exception
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
                $comment->datetime = $this->getTimestamp($comment->datetime);
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

    /**
     * @throws Exception
     */
    private function addQuestion($doc, Index $index): void
    {
        try {
            $topic = json_decode($doc, false, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            echo $file . ": " . $e->getMessage() . "\n";
        }

        $topic->question->datetime = $this->getTimestamp($topic->question->datetime);
        $index->addDocument($topic->question);

        // Записываем ИД вопроса
        $question_id = (int)$topic->question->data_id;

        if ($topic->linked_question) {
            foreach ($topic->linked_question as $key => $linkedQuestion) {
                $linkedQuestion->position = $key + 1;
                $linkedQuestion->datetime = $this->getTimestamp($linkedQuestion->datetime);
                $index->addDocument($linkedQuestion);

                $data_id = property_exists($linkedQuestion, 'data_id') ? $linkedQuestion->data_id : 0;
                $question = Question::create(
                    Id::generate(),
                    (int)$data_id,
                    (int)$linkedQuestion->parent_id,
                    $linkedQuestion->position,
                    $linkedQuestion->username,
                    $linkedQuestion->role,
                    $linkedQuestion->text,
                    $this->getDateFromTimestamp((int)$linkedQuestion->datetime)
                );
                $this->questionRepository->save($question);
            }
        }

        foreach ($topic->comments as $key => $questionComment) {
            $questionComment->position = $key + 1;

            $questionComment->datetime = $this->getTimestamp($questionComment->datetime);

            $index->addDocument($questionComment);

            try {
                $comment = $this->commentRepository->getByDataId((int)$questionComment->data_id);
            } catch (DomainException $e) {
                $comment = Comment::create(
                    Id::generate(),
                    (int)$questionComment->data_id,
                    (int)$questionComment->parent_id,
                    (int)$questionComment->position,
                    $questionComment->username,
                    $questionComment->role,
                    trim($questionComment->text),
                    $this->getDateFromTimestamp($questionComment->datetime)
                );

                $this->commentRepository->save($comment);
            }
        }

        try {
            $question = $this->questionRepository->getByDataId((int)$topic->question->data_id);
        } catch (DomainException $e) {
            $question = Question::create(
                Id::generate(),
                (int)$topic->question->data_id,
                (int)$topic->question->parent_id,
                0,
                $topic->question->username,
                $topic->question->role,
                $topic->question->text,
                $this->getDateFromTimestamp((int)$topic->question->datetime)
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

    /**
     * @throws Exception
     */
    private function getTimestamp(string $datetime): int
    {
        $date = new DateTimeImmutable($datetime);
        return $date->getTimestamp();
    }

    private function getDateFromTimestamp(int $timestamp): DateTimeImmutable
    {
        $date = new DateTimeImmutable();
        return $date->setTimestamp($timestamp);
    }
}
