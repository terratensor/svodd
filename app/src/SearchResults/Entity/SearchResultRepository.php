<?php

declare(strict_types=1);

namespace App\SearchResults\Entity;

use App\Id\Entity\Id;
use RuntimeException;

class SearchResultRepository
{
    /**
     * @param Id $id
     * @return SearchResult
     * @throws \DomainException
     */
    public function get(Id $id): SearchResult
    {
        if (!$searchResult = SearchResult::findOne($id)) {
            throw new \DomainException('Сохраненный результат поиска не найден.');
        }
        return $searchResult;
    }

    /**
     * @param SearchResult $searchResult
     * @throws RuntimeException
     */
    public function save(SearchResult $searchResult): void
    {
        if (!$searchResult->save()) {
            throw new RuntimeException('Ошибка сохранения.');
        }
    }

    /**
     * Delete SearchResult
     *
     * @param SearchResult $searchResult
     * @throws RuntimeException
     */
    public function delete(SearchResult $searchResult): void
    {
        if (!$searchResult->delete()) {
            throw new RuntimeException('Ошибка при удалении записи.');
        }
    }

    /**
     * @param Id $userId
     * @return SearchResult[]
     */
    public function getByUserId(Id $userId): array
    {
        return SearchResult::find()
            ->where(['user_id' => $userId])
            ->all();
    }

    /**
     * @param string $shortLink
     * @return SearchResult|null
     */
    public function findByShortLink(string $shortLink): ?SearchResult
    {
        return SearchResult::findOne(['short_link' => $shortLink]);
    }

    /**
     * @param Id $userId
     * @param string $shortLink
     * @return SearchResult|null
     */
    public function getByUserIdAndShortLink(Id $userId, string $shortLink): ?SearchResult
    {
        return SearchResult::findOne(['user_id' => $userId, 'short_link' => $shortLink]);
    }
}
