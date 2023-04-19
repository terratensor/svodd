<?php

declare(strict_types=1);

namespace App\Indexer\Service;

use App\Indexer\Model\Comment;
use App\Indexer\Model\RelatedQuestion;
use App\Indexer\Model\Topic;
use App\Question\Entity\Question\CommentRepository;
use App\Question\Entity\Question\QuestionRepository;
use App\services\TransactionManager;

class TopicRenewService
{

    private QuestionRepository $questionRepository;
    private CommentRepository $commentRepository;
    private TransactionManager $transaction;

    public function __construct(
        QuestionRepository $questionRepository,
        CommentRepository $commentRepository,
        TransactionManager $transaction
    ) {

        $this->questionRepository = $questionRepository;
        $this->commentRepository = $commentRepository;
        $this->transaction = $transaction;
    }

    /**
     * @throws \Throwable
     */
    public function renew(Topic $topic): void
    {
        $this->transaction->wrap(function () use ($topic) {

            $question = $this->questionRepository->findByDataId($topic->question->data_id);

            if ($question === null) {
                return;
            }

            $question->edit(
                $topic->question->username,
                $topic->question->avatar_file,
                $topic->question->role,
                $topic->question->text,
                $topic->question->datetime
            );

            $this->questionRepository->save($question);


            foreach ($topic->relatedQuestions as $key => $topicRelatedQuestion) {
                /** @var $topicRelatedQuestion RelatedQuestion */
                $relatedQuestion = $this->questionRepository->findRelatedQuestion($question->data_id, $key + 1);

                if ($relatedQuestion === null) {
                    continue;
                }

                $relatedQuestion->edit(
                    $topicRelatedQuestion->username,
                    $topicRelatedQuestion->avatar_file,
                    $topicRelatedQuestion->role,
                    $topicRelatedQuestion->text,
                    $topicRelatedQuestion->datetime
                );
                $this->questionRepository->save($relatedQuestion);
            }

            foreach ($topic->comments as $questionComment) {
                /** @var Comment $questionComment * */

                $comment = $this->commentRepository->findByDataId($questionComment->data_id);

                if ($comment === null) {
                    continue;
                }

                $comment->edit(
                    $questionComment->username,
                    $questionComment->avatar_file,
                    $questionComment->role,
                    $questionComment->text,
                    $questionComment->datetime
                );

                $this->commentRepository->save($comment);
            }
        });
    }
}
