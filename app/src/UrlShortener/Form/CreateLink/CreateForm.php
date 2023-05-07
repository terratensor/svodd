<?php

declare(strict_types=1);

namespace App\UrlShortener\Form\CreateLink;

use yii\base\Model;

class CreateForm extends Model
{
    public string $origin = '';

    public function rules(): array
    {
        return [
            YII_ENV_PROD ? ['origin', 'url'] : ['origin', 'string'],
        ];
    }

    public function formName(): string
    {
        return 'shortLinkCreateForm';
    }
}
