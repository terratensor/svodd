<?php

declare(strict_types=1);

namespace App\SearchResults\Entity;

use App\Id\Entity\Id;
use yii\db\ActiveRecord;

/**
 * @property string $id 
 * @property string $user_id 
 * @property string $short_link
 */
class SearchResult extends ActiveRecord
{
    public static function create(
        Id $id,
        Id $user_id,
        string $short_link,
    ): self {
        $searchResult = new static();

        $searchResult->id = $id;
        $searchResult->user_id = $user_id;
        $searchResult->short_link = $short_link;

        return $searchResult;
    }

    public static function tableName(): string
    {
        return '{{%search_results}}';
    }
}
