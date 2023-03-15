<?php

namespace App\Sentry;

use yii\log\Target;

class SentryTarget extends Target
{

    public function export()
    {
        $messages = array_map([$this, 'formatMessage'], $this->messages);
        foreach ($messages as $message) {
            \Sentry\captureMessage($message);
        }
    }
}
