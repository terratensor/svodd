<?php

declare(strict_types=1);

namespace App\services;

use App\forms\SearchForm;
use App\models\Question;
use App\repositories\Question\QuestionRepository;
use Manticoresearch\ResultSet;

/**
 * Class ManticoreService
 * @packaage App\services
 * @author Aleksey Gusev <audetv@gmail.com>
 */
class ManticoreService
{
    private QuestionRepository $questionRepository;

    public function __construct(QuestionRepository $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    public function search(SearchForm $form, int $page): ResultSet
    {
        $queryString = $form->query;
        return $this
            ->questionRepository
            ->findByQueryString($queryString, $page);
    }

    public function question(int $id, int $page): Question
    {
        $comments = $this
            ->questionRepository
            ->findCommentsByQuestionId($id, $page);

        $questionBody = $this
            ->questionRepository
            ->findQuestionById($id);

        $linkedQuestions = $this
            ->questionRepository
            ->findLinkedQuestionsById($id);

        return Question::create(
            $id,
            $questionBody,
            $linkedQuestions,
            $comments
        );
    }
}
