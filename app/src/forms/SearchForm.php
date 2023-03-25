<?php
declare(strict_types=1);

namespace App\forms;

use yii\base\Model;

/**
 * Class SearchForm
 * @packaage App\forms
 * @author Aleksey Gusev <audetv@gmail.com>
 */
class SearchForm extends Model
{
    public string $query = '';
    public string $matching = 'match';

    public function rules(): array
    {
        return [
            ['query', 'string'],
            ['matching', 'in', 'range' => array_keys($this->getMatching())],
        ];
    }

    public function getMatching(): array
    {
        return [
            'match' => 'По умолчанию',
            'match_phrase' => 'По соответствию фразе',
            'query_string' => 'По строке запроса',
            'in' => 'По номеру(ам) комментария или вопроса',
        ];
    }

    public function formName(): string
    {
        return 'search';
    }
}
