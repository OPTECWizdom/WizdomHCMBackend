<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 23/08/2017
 * Time: 11:05
 */

namespace backend\controllers;

use backend\rest\controllers\AbstractWizdomActiveController;

class VacacionesController extends AbstractWizdomActiveController
{
    private $workflowManager =  "VacacionesWorkflowManager";
    public $modelClass = 'backend\models\movimientosVacaciones\MovimientoVacaciones';





    public function actions()
    {
        $actions = parent::actions();
        $newActions = [

            'create' => [
                'class' => 'backend\actions\controllers\workflows\CreateAction',
                'checkAccess' => [$this, 'checkAccess'],
                'workflowManager' => $this->workflowManager,
                'modelClass' => $this->modelClass,
                'scenario' => 'register'


            ],
            'update' => [
                'class' => 'backend\actions\controllers\workflows\UpdateAction',
                'checkAccess' => [$this, 'checkAccess'],
                'workflowManager' => $this->workflowManager,
                'modelClass' => $this->modelClass,
                'scenario' => 'update'


            ],
            'delete' => [
                'class' => 'backend\actions\controllers\workflows\DeleteAction',
                'checkAccess' => [$this, 'checkAccess'],
                'workflowManager'=>$this->workflowManager,
                'modelClass' => $this->modelClass

            ],

        ];
        $finalActions = array_merge($actions,$newActions);
        return $finalActions;
    }





}