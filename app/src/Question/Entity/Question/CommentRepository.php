<?php

declare(strict_types=1);

namespace App\Question\Entity\Question;

use DomainException;
use RuntimeException;
use yii\db\ActiveRecord;

class CommentRepository
{
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
    }
}
