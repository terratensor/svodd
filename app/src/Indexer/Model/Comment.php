<?php

declare(strict_types=1);

namespace App\Indexer\Model;

use App\Question\Entity\Question\Comment as DbComment;
use DateTimeImmutable;
use Exception;
use stdClass;

class Comment
{
    public string $username;
    public string $role;
    public string $text;
    public DateTimeImmutable $datetime;
    public int $data_id;
    public int $parent_id;
    public int $position;
    public int $type;
    public string $avatar_file;

    /**
     * @throws Exception
     */
    public function __construct(stdClass $data) {
        $this->username = $data->username;
        $this->role = $data->role;
        $this->text = $data->text;
        $this->datetime = new DateTimeImmutable($data->datetime);
        $this->data_id = (int)$data->data_id;
        $this->parent_id = (int)$data->parent_id;
        $this->type = (int) $data->type;
        $this->avatar_file = $data->avatar_file;
    }

    /**
     * @throws Exception
     * Создает объект Topic RelatedQuestion из данных DB
     */
    public static function createFromDB(DbComment $dbComment): self
    {
        $data = new stdClass();

        $data->username = $dbComment->username;
        $data->role = $dbComment->user_role;
        $data->text = $dbComment->text;
        $data->datetime = $dbComment->datetime->format('H:i d.m.Y');;
        $data->data_id = $dbComment->data_id;
        $data->parent_id = $dbComment->question_data_id;
        $data->type = $dbComment->getType();
        $data->avatar_file = $dbComment->avatar_file;

        return new Comment($data);
    }

    public function getSource(int $key): array
    {
        $source = [];
        $position = $key + 1;
        foreach ($this as $property => $value) {
            if ($property === 'datetime') {
                /** @var DateTimeImmutable $value */
                $value = $value->getTimestamp();
            }
            $source[$property] = $value;
        }
        $source['position'] = $position;
        return $source;
    }
}
