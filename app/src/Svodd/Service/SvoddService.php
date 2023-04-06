<?php

declare(strict_types=1);

namespace App\Svodd\Service;

use App\Question\Entity\Question\Question;
use App\Question\Entity\Question\QuestionRepository;
use App\Svodd\Entity\Chart\Data;
use App\Svodd\Entity\Chart\SvoddChartRepository;
use DomainException;

class SvoddService
{
    private QuestionRepository $questionRepository;

    public array $searchList = [
        "https://xn----8sba0bbi0cdm.xn--p1ai/qa/question/view-",
        "https://фкт-алтай.рф/qa/question/view-"
    ];
    private SvoddChartRepository $svoddChartRepository;

    public function __construct(
        QuestionRepository $questionRepository,
        SvoddChartRepository $svoddChartRepository
    )
    {
        $this->questionRepository = $questionRepository;
        $this->svoddChartRepository = $svoddChartRepository;
    }

    public function changeCurrent(string $url): void
    {
        $question = $this->getQuestionIdFrom($url);

        $current = $this->svoddChartRepository->findCurrent();
        $current->changeActive();
        $this->svoddChartRepository->save($current);

        $new = Data::create($question->data_id, 33, 435403);
        $this->svoddChartRepository->save($new);
    }

    private function getQuestionIdFrom(string $url): Question
    {
        $search = 'https://фкт-алтай.рф/qa/question/view-';

        $len = mb_strlen($search);
        $questionId = str_replace($this->getUnicodeString($search), '', $this->getUnicodeString($url));
        $question_id = (int)$questionId;

        if ($question_id === 0) {

            $search = 'https://xn----8sba0bbi0cdm.xn--p1ai/qa/question/view-';
            $questionId = str_replace($search, '', $url);
            $question_id = (int)$questionId;
        }

        if ($question_id === 0) {
            throw new \DomainException('номер должен быть числом больше нуля');
        }

        try {
            $question = $this->questionRepository->getByDataId($question_id);
        } catch (DomainException $e) {
            throw new DomainException($e->getMessage());
        }

        return $question;
    }

    private function getUnicodeString(string $string): bool|string
    {
        $charset = mb_detect_encoding($string);
        return iconv($charset, "UTF-8", $string);
    }
}
