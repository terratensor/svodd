<?php

declare(strict_types=1);

namespace App\Indexer\Model;

use App\Question\Entity\Question\Question as DbQuestion;
use DateTimeImmutable;
use Exception;
use stdClass;

class RelatedQuestion
{
    public string $username;
    public string $role;
    public string $text;
    public DateTimeImmutable $datetime;
    public int $parent_id;
    public int $type;
    public string $avatar_file;

    /**
     * @throws Exception
     */
    public function __construct(stdClass $data) {
        $this->username = $data->username;
        $this->role = $data->role;
        $this->text = $data->text;
        $this->datetime = new DateTimeImmutable($data->datetime, new \DateTimeZone('UTC'));
        $this->parent_id = (int)$data->parent_id;
        $this->type = (int) $data->type;
        $this->avatar_file = $data->avatar_file;
    }

    /**
     * @throws Exception
     * Создает объект Topic RelatedQuestion из данных DB
     */
    public static function createFromDB(DbQuestion $dbQuestion): self
    {
        $data = new stdClass();

        $data->username = $dbQuestion->username;
        $data->role = $dbQuestion->user_role;
        $data->text = $dbQuestion->text;
        $data->datetime = $dbQuestion->datetime->format('H:i d.m.Y');;
        $data->parent_id = $dbQuestion->parent_data_id;
        $data->type = $dbQuestion->getType();
        $data->avatar_file = $dbQuestion->avatar_file;

        return new RelatedQuestion($data);
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
