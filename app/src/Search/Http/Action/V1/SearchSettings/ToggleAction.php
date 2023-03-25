<?php

declare(strict_types=1);

namespace App\Search\Http\Action\V1\SearchSettings;

use App\Search\Command\ToggleSearchSettings\Request\Command;
use App\Search\Command\ToggleSearchSettings\Request\Handler;
use App\Search\Form\SearchSettings\ToggleForm;
use Yii;
use yii\base\Action;

/**
 * Action которая отвечает за переключение видимости панели фильтра поиска
 */
class ToggleAction extends Action
{
    private Handler $handler;

    public function __construct($id, $controller, Handler $handler, $config = [])
    {
        parent::__construct($id, $controller, $config);
        $this->handler = $handler;
    }

    public function run()
    {
        $form = new ToggleForm();
        $session = Yii::$app->session;
        try {
            if ($form->load(Yii::$app->request->post(), '') && $form->validate()) {
                $command = new Command();
                $command->value = $form->value;
                $command->session = $session;
                $this->handler->handle($command);
            }
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
        }
    }
}
