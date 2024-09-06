<?php

declare(strict_types=1);

namespace App\services;

use App\forms\SearchForm;
use App\helpers\SearchHelper;
use App\models\QuestionView;
use App\repositories\Question\QuestionDataProvider;
use App\repositories\Question\QuestionRepository;
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

    /**
     * @param SearchForm $form
     * @return QuestionDataProvider
     * @throws EmptySearchRequestExceptions
     */
    public function search(SearchForm $form): QuestionDataProvider
    {
        $queryString = $form->query;

        $queryString = SearchHelper::processAvatarUrls($queryString);
        $queryTransformedString = '';
      
        // If the query string does not contain any regex patterns, we can transform it to match common user queries
        // This is useful for cases like "[fhhbc", "харрис".
        if (!SearchHelper::containsRegexPattern($queryString) && !SearchHelper::containsURL($queryString)) {
            // Transform the query string to match common user queries
            $queryTransformedString = SearchHelper::transformString($queryString);
        }

        if ($form->dictionary) {
            $indexName = \Yii::$app->params['indexes']['concept'];
        }

        try {
            $comments = match ($form->matching) {
                'query_string' => $this->questionRepository
                    ->findByQueryStringNew($queryString, $indexName ?? null, $form),
                'match_phrase' => $this->questionRepository
                    ->findByMatchPhrase($queryString, $indexName ?? null, $form),
                'match' => $this->questionRepository
                    ->findByQueryStringMatch($queryString, $indexName ?? null, $form),
                'in' => $this->questionRepository
                    ->findByCommentId($queryString, $indexName ?? null, $form),
                //            default => $this->questionRepository
                //                ->findByQueryStringNew($queryString, $indexName ?? null, $form),
            };
        } catch (\DomainException $e) {
            throw new EmptySearchRequestExceptions($e->getMessage());
        }

        $queryTransformed = false;
        if ($comments->get()->getTotal() === 0 && $queryTransformedString !== '') {
            $comments2 = $this->getComments($queryTransformedString, $form, $indexName ?? null);
            if ($comments2->get()->getTotal() > 0) {
                $comments = $comments2;
                $queryTransformed = true;
            }
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
                        'datetime' => [
                            'asc' => ['datetime' => SORT_ASC, 'position' => SORT_DESC],
                            'desc' => ['datetime' => SORT_DESC, 'position' => SORT_ASC],
                        ],
                        'comments_count',
                    ]
                ],
                'queryTransformed' => $queryTransformed,
                'queryTransformedString' => $queryTransformedString
            ]
        );
    }

    public function index(): QuestionDataProvider
    {
        $form = new SearchForm();
        $comments = $this->questionRepository->findByQueryStringNew('', $indexName ?? null, $form);

        return new QuestionDataProvider(
            [
                'query' => $comments,
                'pagination' => [
                    'pageSize' => Yii::$app->params['questions']['pageSize'],
                ],
                'sort' => [
                    'defaultOrder' => [
                        'datetime' => SORT_DESC,
                        'position' => SORT_ASC,
                    ],
                    'attributes' => [
                        'type',
                        'position',
                        'datetime' => [
                            'asc' => ['datetime' => SORT_ASC, 'position' => SORT_DESC],
                            'desc' => ['datetime' => SORT_DESC, 'position' => SORT_ASC],
                        ],
                        'comments_count',
                    ]
                ],
                'queryTransformed' => false,
                'queryTransformedString' => false
            ]
        );
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
            ]
        );

        return QuestionView::create(
            $id,
            $questionBody,
            $linkedQuestions,
            $commentsDataProvider
        );
    }

    private function getComments($queryString, $form, $indexName = null): Search
    {
        try {
            $comments = match ($form->matching) {
                'query_string' => $this->questionRepository
                    ->findByQueryStringNew($queryString, $indexName ?? null, $form),
                'match_phrase' => $this->questionRepository
                    ->findByMatchPhrase($queryString, $indexName ?? null, $form),
                'match' => $this->questionRepository
                    ->findByQueryStringMatch($queryString, $indexName ?? null, $form),
                'in' => $this->questionRepository
                    ->findByCommentId($queryString, $indexName ?? null, $form),
                //            default => $this->questionRepository
                //                ->findByQueryStringNew($queryString, $indexName ?? null, $form),
            };
            return $comments;
        } catch (\DomainException $e) {
            throw new EmptySearchRequestExceptions($e->getMessage());
        }
    }
}
