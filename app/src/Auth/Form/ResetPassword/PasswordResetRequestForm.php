<?php

namespace App\Auth\Form\ResetPassword;

use App\Auth\Entity\User\Status;
use yii\base\Model;
use common\models\User;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public string $email = '';

    public function rules(): array
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => \App\Auth\Entity\User\User::class,
                'filter' => ['status' => Status::ACTIVE],
                'message' => 'Пользователя с этим адресом электронной почты нет.'
            ],
        ];
    }
}
