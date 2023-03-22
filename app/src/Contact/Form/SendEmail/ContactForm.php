<?php

namespace App\Contact\Form\SendEmail;

use common\auth\Identity;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public string $name = '';
    public string $email = '';
    public string $subject = '';
    public string $body = '';

    public function __construct(?Identity $identity = null, $config = [])
    {
        parent::__construct($config);
        if ($identity) {
            $this->email = $identity->getEmail();
        }
    }

    public function rules(): array
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'subject', 'body'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Имя',
            'subject' => 'Тема сообщения',
            'body' => 'Текст сообщения'
        ];
    }
}
