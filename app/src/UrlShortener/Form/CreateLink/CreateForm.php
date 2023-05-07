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
            ['origin', 'url'],
        ];
    }
}
