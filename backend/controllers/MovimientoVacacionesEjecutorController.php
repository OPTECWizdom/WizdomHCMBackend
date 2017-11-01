<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 31/10/2017
 * Time: 16:47
 */

namespace backend\controllers;


use yii\rest\ActiveController;

class MovimientoVacacionesEjecutorController extends ActiveController
{
    private $workflowManager =  "MovimientoVacacionesEjecutorManager";
    public $modelClass = 'app\models\MovimientosVacaciones';


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