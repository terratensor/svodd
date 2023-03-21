<?php

namespace App\Feedback\Form\SendMessage;

use yii\base\Model;

class FeedbackForm extends Model
{
    public string $text = '';

    public function rules(): array
    {
        return [
            ['text', 'required'],
            ['text', 'string'],
        ];
    }
}
