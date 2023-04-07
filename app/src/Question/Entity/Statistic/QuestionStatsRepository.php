<?php

namespace App\Question\Entity\Statistic;

use App\Svodd\Entity\Chart\Data;
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
        $questionIds = Data::find()
            ->alias('sd')
            ->select('sd.question_id')
            ->orderBy('sd.topic_number')
            ->asArray()
            ->column();

        return QuestionStats::find()
            ->alias('qs')
            ->joinWith('svoddData sd')
            ->andWhere(['in', 'qs.question_id', $questionIds])
            ->orderBy("sd.topic_number DESC")
            ->all();
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
