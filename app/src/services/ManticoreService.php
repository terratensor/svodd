<?php

declare(strict_types=1);

namespace App\services;

use App\forms\SearchForm;
use App\helpers\SearchHelper;
use App\models\QuestionView;
use App\repositories\Question\QuestionDataProvider;
use App\repositories\Question\QuestionRepository;
use Manticoresearch\Exceptions\ResponseException;
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
        $indexName = $this->getSearchIndexName($form);
        $queryString = $form->query;

        $queryString = SearchHelper::processAvatarUrls($queryString);

        try {
            $comments = match ($form->matching) {
                'query_string' => $this->questionRepository->findByQueryStringNew($queryString, $indexName ?? null, $form),
                'match_phrase' => $this->questionRepository->findByMatchPhrase($queryString, $indexName ?? null, $form),
                'match' => $this->questionRepository->findByQueryStringMatch($queryString, $indexName ?? null, $form),
                'in' => $this->questionRepository->findByCommentId($queryString, $indexName ?? null, $form),
            };
        } catch (\DomainException $e) {
            throw new EmptySearchRequestExceptions($e->getMessage());
        }

        $suggestQueryString = $this->questionRepository->queryStringSuggestor($queryString, $indexName);

        // Этот вызов необходим для того, чтобы получить как можно раньше исключение с уровня сервиса и обработать исключение на уровне контроллера
        // Иначе все синтаксически неверные полнотекстовые запросы будут выводить исключения на уровне предстовления.
        // Особенность dataprovider, когда на уровне view вызывается поиск, можно сказать это почти классический запрос на уровне представления (view).
        try {
            $comments->get()->getTotal();
        } catch (ResponseException $e) {
            throw $e;
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
                'suggestQueryString' => $suggestQueryString
            ]
        );
    }

    public function index(): QuestionDataProvider
    {
        $form = new SearchForm();
        $indexName = $this->getSearchIndexName($form);
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
                'indexed_documents' => $this->questionRepository->getTotalIndexedDocuments(),
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

    /**
     * Returns the name of the search index based on the form settings.
     *
     * @param SearchForm $form
     * @return string
     */
    private function getSearchIndexName(SearchForm $form): string
    {
        $indexName = \Yii::$app->params['indexes']['common'];
        if ($form->dictionary) {
            $indexName = \Yii::$app->params['indexes']['concept'];
        }

        return $indexName;
    }
}
