<?php 

declare(strict_types=1);

namespace frontend\widgets\bookmark;

use App\Bookmark\Entity\Comment\BookmarkRepository;
use Yii;
use yii\base\Widget;

Class BookmarkWidget extends Widget
{

    private $bokmarkRepository;
    public function __construct(BookmarkRepository $bookmarkRepository, array $config = []) {
        $this->bokmarkRepository = $bookmarkRepository;
        parent::__construct($config);
    }
    public $model;
    public function run()
    {
        $bookmark = $this->bokmarkRepository->getBy(Yii::$app->user->id, $this->model->data_id);
        return $this->render('bookmark', ['bookmark' => $bookmark, 'model' => $this->model]);
    }
}