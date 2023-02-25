<?php
declare(strict_types=1);

namespace App\forms\Manticore;


use yii\base\Model;

/**
 * Class IndexCreateForm
 * @packaage App\forms\Manticore
 * @author Aleksey Gusev <audetv@gmail.com>
 */
class IndexCreateForm extends Model
{
    public string $name = '';

    public function rules(): array
    {
        return [
            ['name', 'string'],
        ];
    }
}
