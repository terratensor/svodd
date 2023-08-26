<?php

declare(strict_types=1);

namespace App\Svodd\Entity\Chart\events;

class StartCommentDataIDSetter
{
    public int $question_id;

    public function __construct(int $question_id) {
        $this->question_id = $question_id;
    }
}
