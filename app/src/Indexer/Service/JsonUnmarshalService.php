<?php

declare(strict_types=1);

namespace App\Indexer\Service;

use App\Indexer\Model\Topic;
use JsonException;

class JsonUnmarshalService
{
    public function handle(string $data): Topic
    {
        try {
            $document = json_decode($data, false, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new \DomainException("JsonUnmarshalService: " . $e->getMessage());
        }

        return new Topic($document);
    }
}
