<?php

declare(strict_types=1);

namespace App\Cabinet\Form\UpdateTopic;

use yii\base\Model;

class UpdateTopicForm extends Model
{
    /**
     * @var string адрес страницы вопроса, следующей темы
     */
    public string $url = '';

    public function rules(): array
    {
        return [
            [['url'], 'trim'],
            [['url'], 'required'],
            [['url'], 'url'],
        ];
    }
}
