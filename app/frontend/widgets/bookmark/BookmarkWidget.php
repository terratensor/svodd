<?php

declare(strict_types=1);

namespace frontend\widgets\bookmark;

use App\Bookmark\Entity\Comment\BookmarkRepository;
use App\Question\Entity\Question\Comment;
use Yii;
use yii\base\Widget;

class BookmarkWidget extends Widget
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
        $options = ['model' => $this->model];
        if (!Yii::$app->user->isGuest) {
            $bookmark = $this->bokmarkRepository->getBy(Yii::$app->user->id, $this->model->data_id);
            $options = array_merge($options, ['bookmark' => $bookmark]);
        }
        return $this->render('bookmark', $options);
    }
}
