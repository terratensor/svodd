<?php

declare(strict_types=1);

namespace App\Svodd\Service;

use App\Question\Entity\Question\QuestionRepository;
use DomainException;

class SvoddService
{
    private QuestionRepository $questionRepository;

    public array $searchList = [
        "https://xn----8sba0bbi0cdm.xn--p1ai/qa/question/view-",
        "https://фкт-алтай.рф/qa/question/view-"
    ];

    public function __construct(QuestionRepository $questionRepository) {
        $this->questionRepository = $questionRepository;
    }

    public function changeCurrent(string $url): void
    {
        $question_id = $this->getQuestionIdFrom($url);

    }

    private function getQuestionIdFrom(string $url): int
    {
        $questionId = '';

        foreach ($this->searchList as $search) {
            $len = mb_strlen($search);
            $questionId = str_replace($this->getUnicodeString($search),'', $this->getUnicodeString($url));
            echo $len; echo mb_strlen($questionId); echo $questionId;
            if (!$len === mb_strlen($questionId)){
                continue;
            }
                break;
        }

        $question_id = (int)$questionId;

        if ($question_id === 0) {
           throw new \DomainException('номер должен быть числом больше нуля');
        }

        try {
            $question = $this->questionRepository->getByDataId($question_id);
        } catch (DomainException $e) {
            throw new DomainException($e->getMessage());
        }

        echo $question_id;
        return $question_id;
    }

    private function getUnicodeString(string $string): bool|string
    {
        $charset = mb_detect_encoding($string);
        return iconv($charset, "UTF-8", $string);
    }
}
