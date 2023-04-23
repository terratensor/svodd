<?php

declare(strict_types=1);

namespace App\services;

use App\forms\SearchForm;
use App\helpers\SearchHelper;
use App\models\QuestionView;
use App\repositories\Question\QuestionDataProvider;
use App\repositories\Question\QuestionRepository;
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
        $queryString = $form->query;

        $queryString = SearchHelper::processAvatarUrls($queryString);

        if ($form->dictionary) {
            $indexName = 'questions_ext';
        }

        $comments = match ($form->matching) {
            'query_string' => $this->questionRepository->findByQueryStringNew($queryString, $indexName ?? null),
            'match_phrase' => $this->questionRepository->findByMatchPhrase($queryString, $indexName ?? null),
            'match' => $this->questionRepository->findByQueryStringMatch($queryString, $indexName ?? null),
            'in' => $this->questionRepository->findByCommentId($queryString, $indexName ?? null),
            default => $this->questionRepository->findByQueryStringNew($queryString, $indexName ?? null),
        };

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
