<?php

declare(strict_types=1);

namespace App\Bookmark\Http\Action\V1\Bookmark\Comment;

use App\Auth\Entity\User\Id as UserId;
use App\Bookmark\Command\Create\Command;
use App\Bookmark\Command\Create\Handler;
use App\Bookmark\Form\BookmarkForm;
use Yii;
use yii\base\Action;

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
        $command = new Command();
        $command->user_id = Yii::$app->user->getId();
        $command->comment_id = $id;

        $this->handler->handle($command);

        return '';
    }
}
