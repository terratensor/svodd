<?php

namespace App\models;

use yii\base\Model;

/**
 * @property string $id
 * @property string $num
 * @property string $date
 * @property string $url
 * @property string $comments
 */
class ListItem extends Model
{
    public string $id;
    public string $num;
    public string $date;
    public string $url;
    public string $comments;
}
