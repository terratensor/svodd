<?php

declare(strict_types=1);

namespace App\models;

use Manticoresearch\ResultSet;
use yii\base\Model;

class Question extends Model
{
    public int $id = 0;
    public ResultSet $body;
    public ResultSet $linkedQuestions;
    public ResultSet $comments;

    public static function create(
        int $id,
        ResultSet $body,
        ResultSet $linkedQuestions,
        ResultSet $comments
    ): self {
        $question = new static();

        $question->id = $id;
        $question->body = $body;
        $question->linkedQuestions = $linkedQuestions;
        $question->comments = $comments;

        return $question;
    }
}
