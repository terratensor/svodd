<?php

declare(strict_types=1);

namespace App\Indexer\Model;


use ArrayObject;

class Topic
{
    public Question $question;
    public ArrayObject $relatedQuestions;
    public ArrayObject $comments;

    public function __construct(\stdClass $data)
    {
        $this->relatedQuestions = new ArrayObject();
        $this->comments = new ArrayObject();

        try {
            foreach ($data as $property => $value) {
                switch ($property) {
                    case "question":
                        $this->question = new Question($value);
                        break;
                    case "linked_question";
                        if ($value === null) {
                            break;
                        }
                        foreach ($value as $relatedQuestion) {
                            $this->relatedQuestions->append(new RelatedQuestion($relatedQuestion));
                        }
                        break;
                    case "comments";
                        if ($value === null) {
                            break;
                        };
                        foreach ($value as $dataComment)
                            $this->comments->append(new Comment($dataComment));
                        break;
                }
            }
        } catch (\Exception $e) {
            throw new \DomainException($e->getMessage());
        }
    }

    public function addRelatedQuestion(Question $question): void
    {
        $this->relatedQuestions->append($question);
    }

    public function addComment(Comment $comment): void
    {
        $this->comments->append($comment);
    }
}
