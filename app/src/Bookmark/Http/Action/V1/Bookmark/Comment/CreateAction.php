<?php

declare(strict_types=1);

namespace App\Bookmark\Http\Action\V1\Bookmark\Comment;

use App\Bookmark\Command\Create\Command;
use App\Bookmark\Command\Create\Handler;
use Yii;
use yii\base\Action;
use yii\base\Response;
use yii\web\NotFoundHttpException;

class CreateAction extends Action
{
    private Handler $handler;

    public function __construct($id, $controller, Handler $handler, $config = [])
    {
        parent::__construct($id, $controller, $config);
        $this->handler = $handler;
    }

    public function run($id)
    {
        $user = Yii::$app->user;

        if (!Yii::$app->request->isPost) {
            Yii::$app->response->statusCode = 404;
            return json_encode(['message' => 'error']);
        }

        // If the user is not logged in, redirect him to the login page
        // and remember the current page in the session.
        if ($user !== false && $user->getIsGuest()) {
            $referer = Yii::$app->request->getReferrer() . "#bookmark-{$id}";
            \Yii::$app->session->set('bookmark_REFERER', $referer);
            return $user->loginRequired();
        }

        $command = new Command();
        $command->user_id = Yii::$app->user->getId();
        $command->comment_id = $id;

        $result = $this->handler->handle($command);

        return json_encode(['bookmark' => $result]);
    }
}
