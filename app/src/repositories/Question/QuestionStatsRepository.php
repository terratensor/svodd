<?php

namespace App\repositories\Question;

use App\models\QuestionStats;
use yii\db\ActiveRecord;

class QuestionStatsRepository
{

    /**
     * @param int $id
     * @return array|ActiveRecord|QuestionStats
     */
    public function getByQuestionId(int $id): array|QuestionStats|ActiveRecord
    {
        if (!$stats =  QuestionStats::find()->andWhere(['question_id' => $id])->one()) {
            throw new \DomainException('Statistics on the question is not found.');
        }
        return $stats;
    }

    public function findAll(): array
    {
        return QuestionStats::find()->orderBy('number')->all();
    }

    public function findAllForList(): array
    {
        return QuestionStats::find()->orderBy("sort ASC, number ASC")->all();
    }

    public function save(QuestionStats $questionStats): void
    {
        if (!$questionStats->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

}
