<?php

namespace App\Question\Entity\Question;

use App\Svodd\Entity\Chart\Data;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\db\Query;

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
     * @deprecated На текущей конфигурации рабочего сервера (512 МБ RAM 1 CPU) выполняется очень долго
     * Было установлено, что время выполнения зависело от ИД вопроса, самые старые от текущей даты комментарии в вопросе рассчитывались до 20 секунд.
     * Находит максимальное значение data_id комментария в вопросе question_data_id
     * @param int $question_data_id
     * @return int|null
     */
    public function findMaxDataIdByQuestion(int $question_data_id): int|null
    {
        return Comment::find()->andWhere(['question_data_id' => $question_data_id])->max('data_id');
    }

    /**
     * Возвращает колонку data_id комментариев к вопросу
     * @param int $question_data_id
     * @return array
     */
    public function findCommentsDataIds(int $question_data_id): array
    {
        return (new Query())
            ->select(['data_id'])
            ->from('question_comments')
            ->where(['question_data_id' => $question_data_id])
            ->orderBy('data_id ASC')
            ->all();
    }

    /**
     * @deprecated На текущей конфигурации рабочего сервера (512 МБ RAM 1 CPU) выполняется очень долго
     * Было установлено, что время выполнения зависело от ИД вопроса, самые старые от текущей даты комментарии в вопросе рассчитывались до 1 минуты.
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
        $questionIds = Data::find()
            ->alias('sd')
            ->select('sd.question_id')
            ->orderBy('sd.topic_number')
            ->asArray()
            ->column();

        $query = Comment::find()
            ->alias('c')
            ->joinWith('svoddData sd')
            ->andWhere(['in', 'c.question_data_id', $questionIds]);

        return new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => [
                    'pageSize' => Yii::$app->params['questions']['pageSize'],
                ],
                'sort' => [
                    'attributes' => [
                        'date' => [
                            'asc' => ['sd.topic_number' => SORT_ASC, 'c.date' => SORT_ASC],
                            'desc' => ['sd.topic_number' => SORT_DESC, 'c.date' => SORT_DESC],
                            'default' => ['sd.topic_number' => SORT_ASC, 'c.date' => SORT_ASC],
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
