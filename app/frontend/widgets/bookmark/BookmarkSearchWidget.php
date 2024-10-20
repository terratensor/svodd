<?php

declare(strict_types=1);

namespace frontend\widgets\bookmark;

use App\Bookmark\Entity\Comment\BookmarkRepository;
use App\models\Comment;
use Yii;
use yii\base\Widget;

class BookmarkSearchWidget extends Widget
{
    private $bokmarkRepository;
    public function __construct(BookmarkRepository $bookmarkRepository, array $config = [])
    {
        $this->bokmarkRepository = $bookmarkRepository;
        parent::__construct($config);
    }
    public Comment $model;
    public function run()
    {
        // Закладки отображаются только для зарегистрированных пользователей
        if (Yii::$app->user->isGuest) {
            return null;
        }

        $options = ['model' => $this->model];

        if ($this->model->type !== Comment::TYPE_COMMENT || $this->model->parent_id === 0) {
            return null;
        }

        if (!Yii::$app->user->isGuest) {
            $bookmark = $this->bokmarkRepository->getBy(Yii::$app->user->id, $this->model->data_id);
            $options = array_merge($options, ['bookmark' => $bookmark]);
        }
        return $this->render('bookmark', $options);
    }
}
