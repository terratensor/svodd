<?php

declare(strict_types=1);

namespace App\Search\Command\ToggleSearchSettings\Request;

/**
 * Обработчик переключает значение в сессии пользователя,
 * которое разрешает или запрещает показ фильтра поиска
 */
class Handler
{
    public function handle(Command $command): void
    {
        if ($command->value === 'toggle') {
            $value = $command->session->get('show_search_settings') ?? false;
            $command->session->set('show_search_settings', !$value);
        }
    }
}
