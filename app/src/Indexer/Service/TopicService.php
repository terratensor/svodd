<?php

declare(strict_types=1);

namespace App\Indexer\Service;

use App\Indexer\Model\Topic;
use App\Question\Entity\Question\Comment;
use App\Question\Entity\Question\CommentRepository;
use App\Question\Entity\Question\Id;
use App\Question\Entity\Question\Question;
use App\Question\Entity\Question\QuestionRepository;

class TopicService
{
    private QuestionRepository $questionRepository;
    private CommentRepository $commentRepository;

    public function __construct(
        QuestionRepository $questionRepository,
        CommentRepository $commentRepository
    ) {
        $this->questionRepository = $questionRepository;
        $this->commentRepository = $commentRepository;
    }

    public function create(Topic $topic): void
    {
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

        foreach ($topic->relatedQuestions as $key => $relatedQuestion) {
            $question = Question::create(
                Id::generate(),
                null,
                $relatedQuestion->parent_id,
                $key + 1,
                $relatedQuestion->username,
                $relatedQuestion->role,
                $relatedQuestion->text,
                $relatedQuestion->datetime
            );
            $this->questionRepository->save($question);
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

            $this->commentRepository->save($comment);
        }
    }
}
