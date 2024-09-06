<?php 

declare(strict_types=1);

namespace App\SearchResults\Entity;

use App\UrlShortener\Service\ViewMyHandler;
use yii\data\BaseDataProvider;
use yii\helpers\ArrayHelper;

class SearchResultDataProvider extends BaseDataProvider
{
    /**
     * @var string|callable|null the column that is used as the key of the data models.
     * This can be either a column name, or a callable that returns the key value of a given data model.
     * If this is not set, the index of the [[models]] array will be used.
     * @see getKeys()
     */
    public $key;
    /**
     * @var array the data that is not paginated or sorted. When pagination is enabled,
     * this property usually contains more elements than [[models]].
     * The array elements must use zero-based integer keys.
     */
    public $allModels;
    /**
     * @var string the name of the [[\yii\base\Model|Model]] class that will be represented.
     * This property is used to get columns' names.
     * @since 2.0.9
     */
    public $modelClass;

    /**
     * @var SearchResult[] $searchResults
     */
    public array $searchResults;

    private ViewMyHandler $handler;

    public function __construct(ViewMyHandler $handler, $config = [])
    {
        parent::__construct($config);
        $this->handler = $handler;
    }

    protected function prepareModels(): array
    {
        $models = [];

         foreach ($this->searchResults as $searchResult) {                 
            $response = $this->handler->handle($searchResult->short_link);
            $searchResult = json_decode($response)[0]; 
            $shortLink = new ShortLink(
                $searchResult->short,
                $searchResult->origin,
                $searchResult->search, 
                $searchResult->redirect_count, 
                $searchResult->created_at
                );            
            $models[] = $shortLink;

            $this->allModels = $models;
        }

        // $pagination = $this->getPagination();

        if (($models = $this->allModels) === null) {
            return [];
        }

        if (($sort = $this->getSort()) !== false) {
            $models = $this->sortModels($models, $sort);
        }

        if (($pagination = $this->getPagination()) !== false) {
            $pagination->totalCount = $this->getTotalCount();

            if ($pagination->getPageSize() > 0) {
                $models = array_slice($models, $pagination->getOffset(), $pagination->getLimit(), true);
            }
        }

        return $models;
    }

 /**
     * {@inheritdoc}
     */
    protected function prepareKeys($models)
    {
        if ($this->key !== null) {
            $keys = [];
            foreach ($models as $model) {
                if (is_string($this->key)) {
                    $keys[] = $model[$this->key];
                } else {
                    $keys[] = call_user_func($this->key, $model);
                }
            }

            return $keys;
        }

        return array_keys($models);
    }

    /**
     * {@inheritdoc}
     */
    protected function prepareTotalCount()
    {    
        return is_array($this->searchResults) ? count($this->searchResults) : 0;
    }

    /**
     * Sorts the data models according to the given sort definition.
     * @param array $models the models to be sorted
     * @param Sort $sort the sort definition
     * @return array the sorted data models
     */
    protected function sortModels($models, $sort)
    {
        $orders = $sort->getOrders();
        if (!empty($orders)) {
            ArrayHelper::multisort($models, array_keys($orders), array_values($orders), $sort->sortFlags);
        }

        return $models;
    }
}