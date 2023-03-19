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
use yii\helpers\ArrayHelper;


class TopicService
{
    private QuestionRepository $questionRepository;
    private CommentRepository $commentRepository;
    private TransactionManager $transaction;
    private QuestionStatsRepository $questionStatsRepository;
    private QuestionIndexService $questionIndexService;
    private StatisticService $statisticService;

    public function __construct(
        QuestionRepository $questionRepository,
        CommentRepository $commentRepository,
        QuestionStatsRepository $questionStatsRepository,
        QuestionIndexService $questionIndexService,
        StatisticService $statisticService,
        TransactionManager $transaction
    ) {
        $this->questionRepository = $questionRepository;
        $this->commentRepository = $commentRepository;
        $this->transaction = $transaction;
        $this->questionStatsRepository = $questionStatsRepository;
        $this->questionIndexService = $questionIndexService;
        $this->statisticService = $statisticService;
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

                $this->commentRepository->save($comment);
            }

            $comment = $comment ?? null;
            $this->updateStatistic($question, $comment);;
        });
    }

    public function update(Topic $topic): void
    {
        $question = $this->questionRepository->getByDataId($topic->question->data_id);
        if (!$question) {
            return;
        }

        // Получаем массив значений data_id комментариев уже записанных в базу данных
        $dbComments = ArrayHelper::getColumn($question->comments, 'data_id');

        // Получаем массив значение data_id комментариев полученных парсингом обновленного файла
        $parsedComments = [];
        foreach ($topic->comments as $parsedComment) {
            $parsedComments[] = $parsedComment->data_id;
        }

        // Сравниваем массивы и оставляем только data_id комментариев, которые отсутствуют в базе данных
        $diff = array_diff($parsedComments, $dbComments);
        echo "будет добавлено " . count($diff) . " новых комментариев\r\n";

        foreach ($diff as $data_id) {

            /** @var \App\Indexer\Model\Comment $parsedComment */
            foreach ($topic->comments as $key => $parsedComment) {

                if ($parsedComment->data_id === $data_id) {
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
                    echo "сохранен в бд комментарий # $comment->data_id \r\n";

                    $this->questionIndexService->addDocument($parsedComment, $key);

                    $this->statisticService->update($question->id);
                }
            }
        }
    }

    private function updateStatistic(Question $question, ?Comment $comment = null): void
    {
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
    }
}
