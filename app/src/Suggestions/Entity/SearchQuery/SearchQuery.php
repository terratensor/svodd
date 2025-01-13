<?php

declare(strict_types=1);

namespace App\Suggestions\Entity\SearchQuery;

use yii\base\Model;

class SearchQuery extends Model
{
    public int $sid;
    public string $query;
    public string $suggestion;

    public static function create(string $query): self
    {
        $sq = new static();
        $sq->suggestion = $query;
        $sq->query = $query;

        return $sq;
    }

    public function populateManticoreID(int $id): void
    {
        $this->sid = $id;
    }
}
