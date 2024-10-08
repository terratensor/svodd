<?php

declare(strict_types=1);

namespace App\Svodd\Entity\Chart;

use App\dispatchers\AppEventDispatcher;
use yii\db\ActiveRecord;

class SvoddChartRepository
{
    private AppEventDispatcher $dispatcher;

    public function __construct(AppEventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }
    /**
     * @return Data[]|array
     */
    public function findAll(): array
    {
        return Data::find()->all();
    }

    /**
     * @return array|Data[]
     */
    public function findAllDesc(): array
    {
        return Data::find()->orderBy('topic_number DESC')->all();
    }

    public function findAllAsc(): array
    {
        return Data::find()->orderBy('topic_number ASC')->all();
    }

    public function save(Data $data): void
    {
        if (!$data->save()) {
            throw new \RuntimeException('Saving error.');
        }
        $this->dispatcher->dispatchAll($data->releaseEvents());    }

    /**
     * @param int $question_id
     * @return array|ActiveRecord|Data|null
     */
    public function findByQuestionId(int $question_id): array|ActiveRecord|null|Data
    {
        return Data::find()->andWhere(['question_id' => $question_id])->one();
    }

    public function findCurrent(): array|ActiveRecord|null|Data
    {
        return Data::find()->andWhere(['active' => true])->one();
    }

    public function findPreviousData(Data $data): array|ActiveRecord|null|Data
    {
        $previous_topic_number = ($data->topic_number - 1);

        return Data::find()->andWhere(['topic_number' => $previous_topic_number])->one();
    }
}
