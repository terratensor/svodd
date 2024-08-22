<?php

declare(strict_types=1);

namespace App\Bookmark\Http\Action\V1\Bookmark\Comment;

use App\Bookmark\Entity\Comment\BookmarkReadModel;
use Yii;
use yii\base\Action;

class ViewAction extends Action
{
    private BookmarkReadModel $bookmarkReadModel;

    public function __construct(
        $id,
        $module,
        BookmarkReadModel $bookmarkReadModel,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->bookmarkReadModel = $bookmarkReadModel;
    }

    public function run(): string|\yii\web\Response
    {
        $user = Yii::$app->user;
        if ($user !== false && $user->getIsGuest()) {
            return $user->loginRequired();
        }

        $dataProvider = $this->bookmarkReadModel->findUserBookmarks(Yii::$app->user->getId());
        return $this->controller->render('view', ['dataProvider' => $dataProvider]);
    }
}