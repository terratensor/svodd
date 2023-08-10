<?php

declare(strict_types=1);

namespace App\Question\Entity\Question\events;

class CommentCreated
{
    public int $data_id;
    public int $question_data_id;
    public string $text;

    /**
     * @param int $data_id
     * @param int $text
     * @param string $text
     */
    public function __construct(int $data_id, int $question_data_id, string $text)
    {
        $this->data_id = $data_id;
        $this->text = $text;
        $this->question_data_id = $question_data_id;
    }
}
