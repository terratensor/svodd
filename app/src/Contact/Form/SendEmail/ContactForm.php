<?php

namespace App\Contact\Form\SendEmail;

use Audetv\YandexSmartCaptcha\Command\CheckCaptcha\Command;
use Audetv\YandexSmartCaptcha\Command\CheckCaptcha\Handler;
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
    public string $token = '';

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
            [['name', 'email', 'subject', 'body', 'token'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            ['token', 'validateToken'],
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

    public function validateToken($attribute, $params): void
    {
        $command = new Command();
        $command->token = $this->token;
        $command->ip = $_SERVER['REMOTE_ADDR'];
        $handler = new Handler("ysc2_b6dOTGUlzkMhda8tddgdztCVs0fpPhPQgDtomuLAded23111");
        $result = $handler->handle($command);
        if (!$result) {
            $this->addError($attribute, 'Вы не прошли проверку капчи. Попробуйте еще раз.');
        }
    }
}
