<?php

declare(strict_types=1);

namespace App\Auth\Form\JoinByEmail;

use yii\base\Model;

class RequestForm extends Model
{
    public string $email = '';
    public string $password = '';

    public function rules(): array
    {
        return [
            [['email', 'password'], 'trim'],
            [['email', 'password'], 'required'],
            [['email'], 'email'],
            [['password'],
                'string',
                'length' => [6],
                'tooShort' => 'Это значение слишком мало. Оно должно содержать 6 символов или более.'
            ],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'password' => 'Пароль'
        ];
    }
}
