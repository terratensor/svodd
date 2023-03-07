<?php

declare(strict_types=1);

namespace App\Indexer\Model;

use DateTimeImmutable;
use Exception;
use stdClass;

class Question
{
    public string $username;
    public string $role;
    public string $text;
    public DateTimeImmutable $datetime;
    public int $data_id;
    public int $type;

    /**
     * @throws Exception
     */
    public function __construct(stdClass $data) {
        $this->username = $data->username;
        $this->role = $data->role;
        $this->text = $data->text;
        $this->datetime = new DateTimeImmutable($data->datetime);
        $this->data_id = (int)$data->data_id;
        $this->type = (int) $data->type;
    }

    public function getSource(): array
    {
        $source = [];
        foreach ($this as $property => $value) {
            if ($property === 'datetime') {
                /** @var DateTimeImmutable $value */
                $value = $value->getTimestamp();
            }
            $source[$property] = $value;
        }
        return $source;
    }
}
