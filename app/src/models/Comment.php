<?php

namespace App\models;

use yii\base\Model;

/**
 * @property int $data_id
 * @property  int $parent_id;
 * @property int $type;
 * @property int $position;
 * @property string $username;
 * @property string $role;
 * @property string $text;
 * @property string $datetime;
 * @property ?string $highlight;
 */
class Comment extends Model
{
    public $data_id;
    public $parent_id;
    public $type;
    public $position;
    public $username;
    public $role;
    public $text;
    public $datetime;
    public $highlight;

    public static function create(
        string $data_id,
        string $parent_id,
        string $type,
        string $position,
        string $username,
        string $role,
        string $text,
        string $datetime,
        ?string $highlight,
    ): self {
        $comment = new static();

        $comment->data_id = $data_id;
        $comment->parent_id = $parent_id;
        $comment->type = $type;
        $comment->position = $position;
        $comment->username = $username;
        $comment->role = $role;
        $comment->text = $text;
        $comment->datetime = $datetime;
        $comment->highlight = $highlight;

        return $comment;
    }
}
