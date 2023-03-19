<?php

declare(strict_types=1);

namespace App\Indexer\Service;

use App\Indexer\Model\Topic;
use App\models\QuestionStats;
use App\Question\Entity\Question\Comment;
use App\Question\Entity\Question\CommentRepository;
use App\Question\Entity\Question\Id;
use App\Question\Entity\Question\Question;
use App\Question\Entity\Question\QuestionRepository;
use App\repositories\Question\QuestionStatsRepository;
use App\services\TransactionManager;
use DateTimeImmutable;
use DomainException;
use Manticoresearch\Index;


class TopicService
{
    private QuestionRepository $questionRepository;
    private CommentRepository $commentRepository;
    private TransactionManager $transaction;
    private QuestionStatsRepository $questionStatsRepository;

    public function __construct(
        QuestionRepository $questionRepository,
        CommentRepository $commentRepository,
        QuestionStatsRepository $questionStatsRepository,
        TransactionManager $transaction
    ) {
        $this->questionRepository = $questionRepository;
        $this->commentRepository = $commentRepository;
        $this->transaction = $transaction;
        $this->questionStatsRepository = $questionStatsRepository;
    }

    public function create(Topic $topic): void
    {
        $this->transaction->wrap(function () use ($topic) {

            $topicQuestion = $topic->question;

            $question = Question::create(
                Id::generate(),
                $topicQuestion->data_id,
                null,
                0,
                $topicQuestion->username,
                $topicQuestion->role,
                $topicQuestion->text,
                $topicQuestion->datetime
            );
            $this->questionRepository->save($question);

            foreach ($topic->relatedQuestions as $key => $topicRelatedQuestion) {
                $relatedQuestion = Question::create(
                    Id::generate(),
                    null,
                    $topicRelatedQuestion->parent_id,
                    $key + 1,
                    $topicRelatedQuestion->username,
                    $topicRelatedQuestion->role,
                    $topicRelatedQuestion->text,
                    $topicRelatedQuestion->datetime
                );

//                $question->addRelatedQuestion($relatedQuestion);
                $this->questionRepository->save($relatedQuestion);
            }

            foreach ($topic->comments as $key => $questionComment) {
                $comment = Comment::create(
                    Id::generate(),
                    $questionComment->data_id,
                    $questionComment->parent_id,
                    $key + 1,
                    $questionComment->username,
                    $questionComment->role,
                    trim($questionComment->text),
                    $questionComment->datetime
                );

//                $question->addComment($comment);
                $this->commentRepository->save($comment);
            }

//            var_dump($question->relatedQuestions);
//            var_dump($question->comments);
//            $this->questionRepository->save($question);
            $lastCommentDate = isset($comment) ? $comment->datetime : new DateTimeImmutable();
            try {
                $stats = $this->questionStatsRepository->getByQuestionId($question->data_id);
                if ($stats->questionDate === null) {
                    $stats->questionDate = $question->datetime;
                }
                $stats->changeCommentsCount(count($question->comments), $lastCommentDate);
            } catch (DomainException $e) {
                $stats = QuestionStats::create(
                    $question->data_id,
                    count($question->comments),
                    count($question->comments) ? $lastCommentDate : null,
                    $question->datetime,
                );
            }
            $this->questionStatsRepository->save($stats);
        });
    }

    public function update(Topic $topic, Index $index): void
    {
        $question = $this->questionRepository->getByDataId($topic->question->data_id);
        if (!$question) {
            return;
        }

        $dbComments = [];
        foreach ($question->comments as $dbComment) {
            $dbComments[] = $dbComment->data_id;
        }

//        var_dump($dbComments);

        $parsedComments = [];
        foreach ($topic->comments as $key => $parsedComment) {
            $parsedComments[] = $parsedComment->data_id;
        }

//        var_dump($parsedComments);

        $diff = array_diff($parsedComments, $dbComments);

//        var_dump($diff);

        foreach ($diff as $data_id) {
//            echo($data_id . "\r\n");
            /** @var \App\Indexer\Model\Comment $parsedComment */
            foreach ($topic->comments as $key => $parsedComment) {
                if ($parsedComment->data_id === $data_id) {
                    echo "\r\n".$key;
                    $comment = Comment::create(
                        Id::generate(),
                        $parsedComment->data_id,
                        $parsedComment->parent_id,
                        $key + 1,
                        $parsedComment->username,
                        $parsedComment->role,
                        trim($parsedComment->text),
                        $parsedComment->datetime
                    );
                    $this->commentRepository->save($comment);
                    $insert = $index->addDocument($parsedComment->getSource($key));
                }
            }
        }
    }
}
