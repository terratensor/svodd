<?php

namespace App\models;

use yii\base\Model;

/**
 * @property int $data_id
 * @property  int $parent_id
 * @property int $type
 * @property int $position
 * @property string $username
 * @property string $avatar_file
 * @property string $role
 * @property string $text
 * @property string $datetime
 * @property string $url
 * @property ?string $highlight
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
    public $avatar_file;
    public $url;

    public static function create(
        string $data_id,
        string $parent_id,
        string $type,
        string $position,
        string $username,
        string $avatar_file,
        string $role,
        string $text,
        string $datetime,
        string $url,
        ?string $highlight,
    ): self {
        $comment = new static();

        $comment->data_id = $data_id;
        $comment->parent_id = $parent_id;
        $comment->type = $type;
        $comment->position = $position;
        $comment->username = $username;
        $comment->avatar_file = $avatar_file;
        $comment->role = $role;
        $comment->text = $text;
        $comment->datetime = $datetime;
        $comment->highlight = $highlight;
        $comment->url = $url;

        return $comment;
    }
}
