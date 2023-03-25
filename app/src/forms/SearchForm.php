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
    public string $in = '';

    public function rules(): array
    {
        return [
            ['query', 'string'],
            ['in', 'boolean']
        ];
    }

    public function formName(): string
    {
        return 'search';
    }
}
