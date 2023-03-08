<?php

declare(strict_types=1);

namespace App\services;

use Yii;

class TransactionManager
{
    /**
     * @param callable $function
     * @throws \Throwable
     */
    public function wrap(callable $function): void
    {
        Yii::$app->db->transaction($function);
    }
}
