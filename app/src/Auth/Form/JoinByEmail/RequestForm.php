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
            [['email', 'password'], 'required'],
            [['email'], 'email'],
            [['password'],
                'string',
                'length' => [6],
                'tooShort' => 'This value is too short. It should have 6 characters or more.'
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
