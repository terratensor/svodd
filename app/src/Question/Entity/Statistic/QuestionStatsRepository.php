<?php

namespace App\Question\Entity\Statistic;

use yii\db\ActiveRecord;

class QuestionStatsRepository
{

    /**
     * @param int $id
     * @return array|ActiveRecord|\App\Question\Entity\Statistic\QuestionStats
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
        return QuestionStats::find()->andWhere(['IS NOT', 'number', null])->orderBy("number DESC")->all();
    }

    public function findSvoddQuestions(): array
    {
        return QuestionStats::find()->andWhere(['IS NOT', 'number', null])->orderBy("number ASC")->all();
    }

    public function save(QuestionStats $questionStats): void
    {
        if (!$questionStats->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

}
