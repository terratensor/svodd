<?php

namespace App\Indexer\Service\IndexFromDB;

use App\Indexer\Model\Comment;
use App\Indexer\Model\RelatedQuestion;
use App\Question\Entity\Question\Question;
use App\Question\Entity\Question\QuestionRepository;
use Manticoresearch\Client;
use Manticoresearch\Index;
use NickBeen\ProgressBar\ProgressBar;

class Handler
{
    private Client $client;
    private QuestionRepository $questionRepository;

    public function __construct(
        Client $client,
        QuestionRepository $questionRepository
    ) {
        $this->client = $client;
        $this->questionRepository = $questionRepository;
    }

    /**
     * @throws \Exception
     */
    public function handle(string $name = 'questions'): void
    {
        $params = ['index' => $name];
        $this->client->indices()->truncate($params);

        $index = new Index($this->client);
        $index->setName($name);

        $questionIDs = Question::find()
            ->andWhere(['is not', 'data_id', null])
            ->select(['id'])
            ->asArray()
            ->all();

        $key = 100 / count($questionIDs);
        $tick = 0;
        echo "Проиндексировано вопросов: \r\n";
        $progressBar = new ProgressBar(maxProgress: 100);
        $progressBar->start();

        foreach ($questionIDs as $questionID) {
            $params = [];
            $question = $this->questionRepository->get($questionID['id']);

            $topicQuestion = \App\Indexer\Model\Question::createFromDB($question);
            $params[] = $topicQuestion->getSource();
//            $index->addDocument($topicQuestion->getSource());

            foreach ($question->relatedQuestions as $relatedQuestion) {
                $topicRelatedQuestion = RelatedQuestion::createFromDB($relatedQuestion);
                $params[] = $topicRelatedQuestion->getSource();
//                $index->addDocument($topicRelatedQuestion->getSource());
            }

            foreach ($question->comments as $comment) {
                $topicQuestionComment = Comment::createFromDB($comment);
                $params[] = $topicQuestionComment->getSource($comment->position - 1);
//                $index->addDocument($topicQuestionComment->getSource($comment->position - 1));
            }

            $index->addDocuments($params);

            $tick = $tick + $key;
            if ($tick >= 1) {
                $progressBar->tick();
                $tick = 0;
            }
        }
        $progressBar->finish();
    }
}
