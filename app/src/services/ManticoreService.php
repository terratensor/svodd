<?php

declare(strict_types=1);

namespace App\services;

use App\forms\SearchForm;
use App\models\QuestionView;
use App\repositories\Question\QuestionDataProvider;
use App\repositories\Question\QuestionRepository;
use Manticoresearch\ResultSet;
use Manticoresearch\Search;
use Yii;

/**
 * Class ManticoreService
 * @packaage App\services
 * @author Aleksey Gusev <audetv@gmail.com>
 */
class ManticoreService
{
    private QuestionRepository $questionRepository;

    public function __construct(QuestionRepository $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    public function search(SearchForm $form): QuestionDataProvider
    {
        if (!$form->in) {
            $queryString = $form->query;
            $comments = $this->questionRepository->findByQueryStringNew($queryString);
        } else {
            $comments = $this->questionRepository->findByCommentId($form->query);
        }

        return new QuestionDataProvider(
            [
                'query' => $comments,
                'pagination' => [
                    'pageSize' => Yii::$app->params['questions']['pageSize'],
                ],
                'sort' => [
                    'attributes' => [
                        'type',
                        'position',
                        'datetime'
                    ]
                ],
            ]);
    }

    public function question(int $id): QuestionView
    {
        $questionBody = $this
            ->questionRepository
            ->findQuestionById($id);

        $linkedQuestions = $this
            ->questionRepository
            ->findLinkedQuestionsById($id);

        $comments = $this->questionRepository->findCommentsByQuestionId($id);

        $commentsDataProvider = new QuestionDataProvider(
            [
                'query' => $comments,
                'pagination' => [
                    'pageSize' => 20,
                ],
                'sort' => [
                    'defaultOrder' => [
                        'type' => SORT_ASC,
                        'position' => SORT_ASC,
                    ],
                    'attributes' => [
                        'type',
                        'position',
                        'datetime'
                    ]
                ],
            ]);

        return QuestionView::create(
            $id,
            $questionBody,
            $linkedQuestions,
            $commentsDataProvider
        );
    }
}
