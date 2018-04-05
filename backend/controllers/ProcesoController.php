<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 04/04/2018
 * Time: 14:51
 */

namespace backend\controllers;


use backend\rest\controllers\AbstractWizdomActiveController;

class ProcesoController extends AbstractWizdomActiveController
{
    private $workflowManager =  "ProcesoWorkflowManager";
    public $modelClass = '';

    public function actions()
    {
        $actions = parent::actions();
        $newActions = [

            'create' => [
                'class' => 'backend\actions\controllers\proceso\CreateAction',
                'checkAccess' => [$this, 'checkAccess'],
                'workflowManager' => $this->workflowManager,
                'scenario' => 'register',
                'modelClass' => $this->modelClass,



            ],
            'update' => [
                'class' => 'backend\actions\controllers\proceso\UpdateAction',
                'checkAccess' => [$this, 'checkAccess'],
                'workflowManager' => $this->workflowManager,
                'scenario' => 'update',
                'modelClass' => $this->modelClass,



            ],
            'delete' => [
                'class' => 'backend\actions\controllers\proceso\DeleteAction',
                'checkAccess' => [$this, 'checkAccess'],
                'workflowManager'=>$this->workflowManager,
                'modelClass' => $this->modelClass,


            ],

        ];
        $finalActions = array_merge($actions,$newActions);
        return $finalActions;
    }


}