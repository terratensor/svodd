<?php

declare(strict_types=1);

namespace App\forms;

use App\helpers\SearchHelper;
use kartik\daterange\DateRangeBehavior;
use yii\base\Model;

/**
 * Class SearchForm
 * @packaage App\forms
 * @author Aleksey Gusev <audetv@gmail.com>
 */
class SearchForm extends Model
{
    public string $query = '';
    public string $date_from = '';
    public string $date_to = '';
    public string $date = '';
    public string $matching = 'query_string';
    public bool $dictionary = false;
    public string $badge = 'all';

    public string $defaultBadge = 'all';

    public function behaviors(): array
    {
        return [
            [
                'class' => DateRangeBehavior::class,
                'attribute' => 'date',
                'dateStartAttribute' => 'date_from',
                'dateEndAttribute' => 'date_to',
            ]
        ];
    }

    public function rules(): array
    {
        return [
            ['query', 'string'],
            [['date'], 'match', 'pattern' => '/^.+\s\-\s.+$/'],
            [['date_from', 'date_to'], 'date', 'format' => 'php:d.m.Y H:i'],
            ['matching', 'in', 'range' => array_keys($this->getMatching())],
            ['badge', 'in', 'range' => array_keys($this->makeBadgeList())],
            ['dictionary', 'boolean']
        ];
    }

    public function getMatching(): array
    {
        return [
            'query_string' => 'Обычный поиск',
            'match_phrase' => 'Точное соответствие',
            'match' => 'Любое слово',
            self::MATCHING_IN => 'По номерам записей через запятую',
        ];
    }

    public function makeBadgeList(): array
    {
        return [
            self::BADGE_ALL => 'ВСЕ',
            'svodd' => 'СВОДД',
            'aq' => "ВОПРОС–ОТВЕТ",
            'comments' => 'КОММЕНТАРИИ',
        ];
    }

    public function formName(): string
    {
        return 'search';
    }

    public function beforeValidate(): bool
    {
        if ($this->matching === self::MATCHING_IN) {
            $this->badge = self::BADGE_ALL;
        }
        // Нормализуем поисковый запрос, удаляем лишние пробелы, url запроса остается без изменения, дл
        // но при следующей отправке нормализованного запроса будет уже обновленный url
        $this->query = SearchHelper::normalizeString($this->query, false);
        
        return parent::beforeValidate();
    }

    private const MATCHING_IN = 'in';
    private const BADGE_ALL = 'all';
}
