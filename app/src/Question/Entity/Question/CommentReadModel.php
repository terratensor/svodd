<?php

namespace App\Question\Entity\Question;

use App\Question\Entity\Statistic\QuestionStats;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

class CommentReadModel
{
    /**
     * Возвращает количество комментариев в вопросе question_data_id
     * @param int $question_data_id
     * @return bool|int|string|null
     */
    public function commentsCountByQuestion(int $question_data_id): bool|int|string|null
    {
        return Comment::find()->andWhere(['question_data_id' => $question_data_id])->count();
    }
    /**
     * Находит комментарий по его data_id
     * @param int $data_id
     * @return array|ActiveRecord|Comment|null
     */
    public function findByDataId(int $data_id): array|ActiveRecord|null|Comment
    {
        return Comment::find()->andWhere(['data_id' => $data_id])->one();
    }

    /**
     * Находит максимальное значение data_id комментария в вопросе question_data_id
     * @param int $question_data_id
     * @return int|null
     */
    public function findMaxDataIdByQuestion(int $question_data_id): int|null
    {
        return Comment::find()->andWhere(['question_data_id' => $question_data_id])->max('data_id');
    }

    /**
     * Находит минимальное значение data_id комментария в вопросе question_data_id
     * @param int $question_data_id
     * @return int|null
     */
    public function findMinDataIdByQuestion(int $question_data_id): int|null
    {
        return Comment::find()->andWhere(['question_data_id' => $question_data_id])->min('data_id');
    }

    public function findBySvoddQuestions(): ActiveDataProvider
    {
        $questionIds = QuestionStats::getDb()->cache(function ($db) {
            return QuestionStats::find()
                ->alias('qs')
                ->select('question_id')
                ->andWhere(['not', ['qs.number' => null]])
                ->orderBy('qs.number')
                ->asArray()
                ->column();
        });

        $query = Comment::find()
            ->alias('c')
            ->joinWith('questionStat qs')
            ->andWhere(['in', 'c.question_data_id', $questionIds])
            ;

        return new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => [
                    'pageSize' => Yii::$app->params['questions']['pageSize'],
                ],
                'sort' => [
                    'attributes' => [
                        'date' => [
                            'asc' => ['qs.number' => SORT_ASC, 'c.date' => SORT_ASC],
                            'desc' => ['qs.number' => SORT_DESC, 'c.date' => SORT_DESC],
                            'default' => ['qs.number' => SORT_ASC, 'c.date' => SORT_ASC],
                        ],
                    ],
                    'defaultOrder' => [
                        'date' => SORT_ASC
                    ],
                ]
            ]
        );
    }
}
