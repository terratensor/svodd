<?php

namespace frontend\controllers;

use App\Contact\Http\Action\V1\Contact\ContactAction;
use App\FeatureToggle\FeatureFlag;
use App\forms\SearchForm;
use App\Question\Entity\Statistic\QuestionStatsRepository;
use App\Search\Http\Action\V1\SearchSettings\ToggleAction;
use App\services\EmptySearchRequestExceptions;
use App\services\ManticoreService;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class SiteController extends Controller
{
    private ManticoreService $service;
    private QuestionStatsRepository $questionStatsRepository;
    private FeatureFlag $flag;

    public function __construct(
        $id,
        $module,
        ManticoreService $service,
        QuestionStatsRepository $questionStatsRepository,
        FeatureFlag $flag,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->questionStatsRepository = $questionStatsRepository;
        $this->flag = $flag;
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
            'contact' => [
                'class' => ContactAction::class,
            ],
            'search-settings' => [
                'class' => ToggleAction::class,
            ]
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex($feature = null): string
    {
        $this->layout = 'search';
        $results = null;
        $form = new SearchForm();
        $errorQueryMessage = '';

        try {
            if ($form->load(Yii::$app->request->queryParams) && $form->validate()) {
                $results = $this->service->search($form);
            }
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        } catch (EmptySearchRequestExceptions $e) {
            $errorQueryMessage = $e->getMessage();
        }

        foreach ($this->flag->features as $key => $value) {
            if ($feature === $key) {
                $this->flag->enable($key);
            }
        }

        return $this->render('index', [
            'results' => $results ?? null,
            'model' => $form,
            'errorQueryMessage' => $errorQueryMessage,
            'flag' => $this->flag ?? null
        ]);
    }

    public function actionQuestion($id): string
    {
        $question = $this->service->question($id);

        return $this->render('question', [
            'question' => $question,
        ]);
    }

    public function actionCurrent(): string
    {
        $list = $this->questionStatsRepository->findAllForList();
        return $this->render('current', ['list' => $list]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
//    public function actionAbout(): string
//    {
//        return $this->render('about');
//    }
}
