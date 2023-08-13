<?php

declare(strict_types=1);

namespace App\Question\Entity\Question;

use App\Question\dispatchers\AppEventDispatcher;
use App\Svodd\Entity\Chart\Data;
use DomainException;
use RuntimeException;
use yii\db\ActiveRecord;

class CommentRepository
{
    private AppEventDispatcher $dispatcher;

    public function __construct(AppEventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function get(string $id): array|ActiveRecord|Comment
    {
        if (($comment = Comment::find()->andWhere(['id' => $id])->limit(1)->one()) === null) {
            throw new DomainException('Comment is not found.');
        }
        return $comment;
    }

    /**
     * @param int $data_id
     * @return array|ActiveRecord|Comment
     */
    public function getByDataId(int $data_id): array|ActiveRecord|Comment
    {
        if (($comment = Comment::find()->andWhere(['data_id' => $data_id])->limit(1)->one()) === null) {
            throw new DomainException('Comment is not found.');
        }
        return $comment;
    }

    public function save(Comment $comment): void
    {
        if (!$comment->save()) {
            throw new RuntimeException('Saving error.');
        }
        $this->dispatcher->dispatchAll($comment->releaseEvents());
    }

    public function findByDataId(int $data_id): array|ActiveRecord|null|Comment
    {
        return Comment::find()->andWhere(['data_id' => $data_id])->one();
    }


    /**
     * Возвращает все комментарии темы вопроса с открывающего комментария
     * используется для нахождения комментариев новой установленной темы
     * для последующей отправки этих комментариев в очередь для отправки в телеграм
     * @param Data $data
     * @return array|ActiveRecord[]
     */
    public function findCommentsByChartData(Data $data): array
    {
        return Comment::find()
            ->andWhere(['question_data_id' => $data->question_id])
            ->andWhere(['>', 'data_id', $data->start_comment_data_id])
            ->orderBy('data_id ASC')
            ->all();
    }
}
