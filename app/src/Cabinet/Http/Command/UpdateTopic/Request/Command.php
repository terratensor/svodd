<?php

declare(strict_types=1);

namespace App\Cabinet\Http\Command\UpdateTopic\Request;

class Command
{
    /**
     * @var string адрес страницы вопроса, следующей темы
     */
    public string $url = '';
    /**
     * @var string номер следующей темы
     */
    public string $number = '';
    /**
     * @var string ИД комментария, открывающего новую тему
     */
    public string $data_id = '';
}
