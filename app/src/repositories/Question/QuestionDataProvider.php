<?php

namespace App\repositories\Question;

use App\models\Comment;
use Manticoresearch\Search;
use yii\data\BaseDataProvider;

class QuestionDataProvider extends BaseDataProvider
{

    /**
     * @var string|callable Имя столбца с ключом или callback-функция, возвращающие его
     */
    public $key;
    /**
     * @var Search
     */
    public Search $query;

    public bool $queryTransformed;
    public string $queryTransformedString;

    protected function prepareModels(): array
    {
        $models = [];
        $pagination = $this->getPagination();
        $sort = $this->getSort();

        foreach ($sort->getOrders() as $attribute => $value) {
            $direction = $value === SORT_ASC ? 'asc' : 'desc';
            $this->query->sort($attribute, $direction);
            if ($attribute === 'comments_count') {
                $this->query->filter('type', 'in', [Comment::TYPE_QUESTION]);
            }
        }

        if ($pagination === false) {
            // в случае отсутствия разбивки на страницы - прочитать все строки
            foreach ($this->query->get() as $hit) {
                $id = $hit->getId();
                $comment = new Comment($hit->getData());
                $comment->populateManticoreID($id);
                $models[] = $comment;
            }
        } else {
            // в случае, если разбивка на страницы есть - прочитать только одну страницу
            $pagination->totalCount = $this->getTotalCount();

            $limit = $pagination->getLimit();
            $offset = $pagination->getOffset();

            $this->query->limit($pagination->pageSize);
            $this->query->offset($offset);

            $maxMatches = $offset + $limit;
            // Устанавливаем max_matches в зависимости от номера выбранной старницы
            $this->query->maxMatches($maxMatches);

            $data = $this->query->get();

            // Если количество записей меньше чем лимит,
            // то переписываем лимит, чтобы избежать ошибки Undefined array key при вызове $data->current()
            if ($data->count() < $limit) {
                $limit = $data->count();
            }

            for ($count = 0; $count < $limit; ++$count) {
                $id = $data->current()->getId();
                $model = new Comment($data->current()->getData());
                $model->populateManticoreID($id);
                try {
                    $model->highlight = $data->current()->getHighlight();
                } catch (\Exception $e) {
                    $model->highlight = null;
                }
                $models[] = $model;
                $data->next();
            }
        }

        return $models;
    }

    protected function prepareKeys($models): array
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
        } else {
            return array_keys($models);
        }
    }

    protected function prepareTotalCount()
    {
        $count = $this->query->get()->getTotal();
        return $count;
    }
}
