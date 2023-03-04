<?php

declare(strict_types=1);

namespace App\Auth\Form\Login;

use yii\base\Model;

class LoginForm extends Model
{
    public string $email = '';
    public string $password = '';
    public bool $rememberMe = true;

    public function rules(): array
    {
        return [
            // username and password are both required
            [['email', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['email', 'email'],
            [['password'],
                'string',
                'length' => [6],
                'tooShort' => 'This value is too short. It should have 6 characters or more.'
            ],
        ];
    }
}
