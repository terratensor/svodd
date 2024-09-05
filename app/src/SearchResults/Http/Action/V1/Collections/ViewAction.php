<?php

declare(strict_types=1);

namespace App\SearchResults\Http\Action\V1\Collections;

use App\Id\Entity\Id;
use App\SearchResults\Entity\SearchResultRepository;
use App\UrlShortener\Service\ViewMyHandler;

class ViewAction extends \yii\base\Action
{
    private SearchResultRepository $repository;
    private ViewMyHandler $handler;
    public function __construct($id, $controller, SearchResultRepository $repository, ViewMyHandler $handler, $config = [])
    {
        parent::__construct($id, $controller, $config);
        $this->repository = $repository;
        $this->handler = $handler;
    }
    public function run()
    {
        $user = \Yii::$app->user;
        if ($user !== false && $user->getIsGuest()) {
            return $user->loginRequired();
        }

        $searchResults = $this->repository->getByUserId(new Id($user->id));

        return $this->controller->render('view', ['searchResults' => $searchResults, 'handler' => $this->handler]);
    }
}
