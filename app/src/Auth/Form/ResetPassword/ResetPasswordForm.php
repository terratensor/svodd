<?php

declare(strict_types=1);

namespace App\Auth\Form\ResetPassword;

use Yii;
use yii\base\Model;

class ResetPasswordForm extends Model
{
    public string $password = '';

    public function rules(): array
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'password' => 'Пароль',
        ];
    }
}
