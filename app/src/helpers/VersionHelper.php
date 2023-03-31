<?php

declare(strict_types=1);

namespace App\helpers;

use yii\helpers\Html;

class VersionHelper
{
    /**
     * Возвращает версию приложения из репозитория github
     * @return string
     */
    public static function version(): string
    {
        $cache = \Yii::$app->cache;

        return $cache->getOrSet('version', function () {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Accept: application/vnd.github+json',
                'X-GitHub-Api-Version: 2022-11-28',
                'User-Agent: fct-search-app'
            ]);
            curl_setopt($ch, CURLOPT_URL, 'https://api.github.com/repos/audetv/fct-search/releases/latest');
            $result = curl_exec($ch);
            curl_close($ch);

            $json = json_decode($result, true);

            $tag_name = $json['tag_name'] ?? '';
            $html_url = $json['html_url'] ?? '';

            if ($tag_name && $html_url) {
                return Html::a($json['tag_name'], $json['html_url'], ['target' => '_blank']);
            }

            return '';
        });
    }
}
