<?php

declare(strict_types=1);

namespace frontend\widgets\search;

use App\models\Comment;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Badge extends Widget
{
    public Comment $comment;
    public array $svodd_questions;
    private $type = 0;

    const TYPE = [
        1 => 'svodd',
        2 => 'aq',
        3 => 'comments'
    ];

    public function run()
    {
        $badge = $this->getBadgeType($this->comment);

        $location =  \yii\helpers\Url::to(BadgeFilter::makeUrl($badge === $this->currentQueryBadge() ? 'all' : $badge));
        $title = $badge === $this->currentQueryBadge() ? 'Отключить фильтр по ' . $this->retrieveBadgeName($badge) : 'Включить фильтр по ' . $this->retrieveBadgeName($badge);

        $res = Html::tag(
            'span',
            $this->retrieveBadgeName($badge),
            [
                'class' => 'badge ' .
                    $this->getBadgeClass($badge),
                'onClick' => 'location.href = "' . $location . '";',
                'title' => $title
            ]
        );
        return $res;
    }

    public function getBadgeType(Comment $comment): string
    {
        if ($comment->type === 4 || $comment->type === 5) {
            return self::TYPE[2];
        }
        if (in_array($comment->data_id, $this->svodd_questions) || in_array($comment->parent_id, $this->svodd_questions)) {
            return self::TYPE[1];
        }
        return self::TYPE[3];
    }

    public function getBadgeList(): array
    {
        return [
            'svodd' => ['name' => 'СВОДД', 'class' => 'badge-svodd'],
            'aq' => ['name' => 'ВОПРОС–ОТВЕТ', 'class' => 'badge-aq'],
            'comments' => ['name' => 'КОММЕНТАРИИ', 'class' => 'badge-comments'],
        ];
    }


    /**
     * @param string $badgeValue
     * @return string
     */
    public function retrieveBadgeName(string $badgeValue): string
    {
        $badgeList = $this->getBadgeList();

        return $badgeList[$badgeValue]['name'] ?? '';
    }

    /**
     * @param string $badgeValue
     * @return string
     */
    public function getBadgeClass(string $badgeValue): string
    {
        $badgeList = $this->getBadgeList();
        return $badgeList[$badgeValue]['class'] ?? '';
    }

    public function currentQueryBadge(): string
    {
        $queryParams = Yii::$app->request->getQueryParams();
        return $queryParams['search']['badge'] ?? '';
    }
}
