<?php

declare(strict_types=1);

namespace App\Svodd\Entity\Chart;

use yii\db\ActiveRecord;

class SvoddChartRepository
{
    public function findAll(): array
    {
        return Data::find()->all();
    }

    public function save(Data $data): void
    {
        if (!$data->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    /**
     * @param int $question_id
     * @return array|ActiveRecord|Data|null
     */
    public function findByQuestionId(int $question_id): array|ActiveRecord|null|Data
    {
        return Data::find()->andWhere(['question_id' => $question_id])->one();
    }
}
