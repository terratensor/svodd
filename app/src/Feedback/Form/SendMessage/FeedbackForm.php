<?php

namespace App\Feedback\Form\SendMessage;

use App\Feedback\Entity\Feedback\Feedback;
use yii\base\Model;

class FeedbackForm extends Model
{
    public string $text = '';

    public function __construct(?Feedback $feedback = null, $config = [])
    {
        if ($feedback) {
            $this->text = $feedback->text;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            ['text', 'required'],
            ['text', 'string'],
        ];
    }
}
