<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 01/09/2017
 * Time: 19:31
 */

namespace backend\controllers;


use yii\rest\ActiveController;

class FlujoProcesoAgenteUpdaterController extends ActiveController
{
    private $workflowManager =  "FlujoProcesoAgenteManager";
    public $modelClass = 'app\models\FlujoProcesoAgente';


    public function actions()
    {
        $oldActions =  parent::actions();
        $actions =  [
            "create"=>[
                'class'=>'backend\actions\controllers\jobs\RunJobAction',
                'workflowManager'=>$this->workflowManager,
                'modelClass' => $this->modelClass
            ]

            ];
        return array_merge($oldActions,$actions);


    }

}