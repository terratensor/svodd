<?php

namespace App\Svodd\Http\Action\V1\Svodd;

use App\Svodd\Entity\Chart\SvoddChartRepository;
use yii\base\Action;

class IndexAction extends Action
{
    private SvoddChartRepository $svoddChartRepository;

    public function __construct(
        $id,
        $module,
        SvoddChartRepository $svoddChartRepository,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->svoddChartRepository = $svoddChartRepository;
    }

    public function run(): string
    {
        $this->controller->layout = 'svodd';
        $data = $this->svoddChartRepository->findAllDesc();
        return $this->controller->render('index', ['data' => $data]);
    }
}
