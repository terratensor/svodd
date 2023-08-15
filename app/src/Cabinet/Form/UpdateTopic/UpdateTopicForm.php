<?php

declare(strict_types=1);

namespace App\Cabinet\Form\UpdateTopic;

use App\Svodd\Entity\Chart\Data;
use yii\base\Model;

class UpdateTopicForm extends Model
{
    /**
     * @var string адрес страницы вопроса, следующей темы
     */
    public string $url = '';
    /**
     * @var string ИД комментария, открывающего новую тему
     */
    public string $data_id = '';

    public function rules(): array
    {
        return [
            [['url', 'data_id'], 'trim'],
            [['url', 'data_id'], 'required'],
            [['url'], 'url'],
            [['data_id'], 'integer'],
            [['data_id'], 'unique', 'targetClass' => Data::class, 'targetAttribute' => 'start_comment_data_id'],
        ];
    }
}
