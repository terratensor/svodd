<?php
declare(strict_types=1);

namespace App\forms\Manticore;


use yii\base\Model;

/**
 * Class IndexDeleteForm
 * @packaage App\forms\Manticore
 * @author Aleksey Gusev <audetv@gmail.com>
 */
class IndexDeleteForm extends Model
{
    public string $name = '';

    public function rules(): array
    {
        return [
            ['name', 'required'],
            ['name', 'string'],
        ];
    }
}
