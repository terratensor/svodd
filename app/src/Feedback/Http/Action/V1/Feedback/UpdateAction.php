<?php

namespace App\Feedback\Http\Action\V1\Feedback;

use App\Auth\Entity\User\Id as UserId;
use App\Auth\Entity\User\UserRepository;
use App\Feedback\Command\EditMessage\Command;
use App\Feedback\Command\EditMessage\Handler;
use App\Feedback\Entity\Feedback\FeedbackRepository;
use App\Feedback\Entity\Feedback\Id as FeedbackId;
use App\Feedback\Form\SendMessage\FeedbackForm;
use Yii;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class UpdateAction extends Action
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
     * @param string $id
     * @return Response|string
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
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

        $form = new FeedbackForm($feedback);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {

            $command = new Command();
            $command->id = $feedback->getId();
            $command->user_id = Yii::$app->user->getId();
            $command->text = $form->text;

            try {
                $this->handler->handle($command);
                return $this->controller->redirect(['index', '#' => 'comment-' . $feedback->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->controller->render('update', [
            'feedback' => $feedback,
            'model' => $form
        ]);
    }
}
