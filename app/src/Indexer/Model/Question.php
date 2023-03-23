<?php

declare(strict_types=1);

namespace App\Indexer\Model;

use App\Question\Entity\Question\Question as DbQuestion;
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

    /**
     * @throws Exception
     * Создает объект Topic Question из данных DB
     */
    public static function createFromDB(DbQuestion $dbQuestion): self
    {
        $data = new stdClass();

        $data->username = $dbQuestion->username;
        $data->role = $dbQuestion->user_role;
        $data->text = $dbQuestion->text;
        $data->datetime = $dbQuestion->datetime->format('H:i d.m.Y');
        $data->data_id = $dbQuestion->data_id;
        $data->type = $dbQuestion->getType();

        return new Question($data);
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
