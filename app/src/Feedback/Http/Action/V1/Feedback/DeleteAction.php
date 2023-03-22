<?php

namespace App\Feedback\Http\Action\V1\Feedback;

use App\Auth\Entity\User\Id as UserId;
use App\Auth\Entity\User\UserRepository;
use App\Feedback\Command\DeleteMessage\Command;
use App\Feedback\Command\DeleteMessage\Handler;
use App\Feedback\Entity\Feedback\FeedbackRepository;
use App\Feedback\Entity\Feedback\Id as FeedbackId;
use Yii;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class DeleteAction extends Action
{
    private UserRepository $userRepository;
    private FeedbackRepository $feedbackRepository;
    private Handler $handler;

    public function __construct(
        $id, $controller,
        UserRepository $userRepository,
        FeedbackRepository $feedbackRepository,
        Handler $handler,
        $config = []
    ) {
        parent::__construct($id, $controller, $config);
        $this->userRepository = $userRepository;
        $this->feedbackRepository = $feedbackRepository;
        $this->handler = $handler;
    }

    /**
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function run(string $id): Response|string
    {
        if (Yii::$app->user->isGuest) {
            throw new NotFoundHttpException('страница не найдена.');
        }

        $user = $this->userRepository->get(new UserId(Yii::$app->user->getId()));
        $feedback = $this->feedbackRepository->get(id: new FeedbackId($id));

        if (!Yii::$app->user->can('editOwnFeedback', ['entity' => $feedback])) {
            throw new ForbiddenHttpException('Доступ запрещен.');
        }

        if (!$feedback->allowedToEdit(new \DateTimeImmutable())) {
            throw new ForbiddenHttpException('Время, в течение которого можно удалить сообщение, истекло.');
        }

        $command = new Command();
        $command->id = $feedback->getId()->getValue();
        $command->user_id = $user->getId()->getValue();

        try {
            $this->handler->handle($command);
            Yii::$app->session->setFlash('success', 'Сообщение удалено.');
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->controller->redirect(['feedback/index']);
    }
}
