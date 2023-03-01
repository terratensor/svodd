<?php

declare(strict_types=1);

namespace App\models;

use App\repositories\Question\QuestionDataProvider;
use Manticoresearch\ResultSet;
use yii\base\Model;

class Question extends Model
{
    public int $id = 0;
    public ResultSet $body;
    public ResultSet $linkedQuestions;
    /**
     * @var mixed|QuestionDataProvider|null
     */
    public QuestionDataProvider $provider;

    public static function create(
        int $id,
        ResultSet $body,
        ResultSet $linkedQuestions,
        QuestionDataProvider $provider
    ): self {
        $question = new static();

        $question->id = $id;
        $question->body = $body;
        $question->linkedQuestions = $linkedQuestions;
        $question->provider = $provider;

        return $question;
    }
}
