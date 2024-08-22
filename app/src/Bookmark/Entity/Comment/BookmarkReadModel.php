<?php 

declare(strict_types=1);

namespace App\Bookmark\Entity\Comment;

use yii\data\ActiveDataProvider;

class BookmarkReadModel
{
    public function findUserBookmarks(string $user_id): ActiveDataProvider
    {
        $query = Bookmark::find()
        ->alias('b')
        ->joinWith('comment c')
        ->andWhere(['b.user_id' => $user_id]);
        return new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => [
                    'pageSize' => \Yii::$app->params['questions']['pageSize'],
                ],
                'sort' => [
                    'attributes' => [
                        'date' => [
                            'asc' => [
                                'b.created_at' => SORT_DESC,
                                'b.comment_data_id' => SORT_DESC,  
                            ],
                            'desc' => [
                                'b.created_at' => SORT_ASC,
                                'b.comment_data_id' => SORT_ASC,  
                            ],
                            'default' => [
                                'b.created_at' => SORT_DESC,
                                'b.comment_data_id' => SORT_DESC,  
                            ],
                        ],
                        'comment-date' => [
                            'asc' => [
                                'c.date' => SORT_DESC,
                                'b.comment_data_id' => SORT_DESC,  
                            ],
                            'desc' => [
                                'c.date' => SORT_ASC,
                                'b.comment_data_id' => SORT_ASC,
                            ],
                            'default' => [
                                'c.date' => SORT_ASC,
                                'b.comment_data_id' => SORT_ASC,                                
                            ],
                        ],
                    ],
                    'defaultOrder' => [
                        'date' => SORT_ASC,
                    ],
                ]
            ]
        );
    }
}