<?php

namespace App\Indexer\Service\IndexFromDB;

use App\Indexer\Model\Comment;
use App\Indexer\Model\RelatedQuestion;
use App\Question\Entity\Question\Question;
use App\Question\Entity\Question\QuestionRepository;
use Manticoresearch\Client;
use Manticoresearch\Index;

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

        foreach ($questionIDs as $questionID) {
            $question = $this->questionRepository->get($questionID['id']);

            $topicQuestion = \App\Indexer\Model\Question::createFromDB($question);
            $index->addDocument($topicQuestion->getSource());
            echo (new \DateTimeImmutable())->format('H:i:s d.m.Y') . ' ' . "Добавлен вопрос $topicQuestion->data_id \r\n";

            foreach ($question->relatedQuestions as $relatedQuestion) {
                $topicRelatedQuestion = RelatedQuestion::createFromDB($relatedQuestion);
                $index->addDocument($topicRelatedQuestion->getSource());
                echo "Добавлен связанный вопрос $topicRelatedQuestion->parent_id \r\n";
            }

            foreach ($question->comments as $comment) {
                $topicQuestionComment = Comment::createFromDB($comment);
                $index->addDocument($topicQuestionComment->getSource($comment->position));
                echo "Добавлен связанный комментарий $comment->data_id \r\n";
            }
        }
    }
}
