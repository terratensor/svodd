<?php

declare(strict_types=1);

namespace frontend\widgets;

use Yii;
use yii\base\Widget;

class MaintenanceWidget extends Widget
{
    public string $message = '';

    public function run(): void
    {
        if (getenv('MAINTENANCE_MODE') === '1') {
            if (!$this->message) {
                $this->message = \Yii::$app->params['maintenance_message'];
            }
            Yii::$app->session->setFlash('success', $this->message);
        }
    }
}
