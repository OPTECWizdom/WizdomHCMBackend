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

    public function actions()
    {
        $actions = parent::actions();
        $newActions = [

            'create' => [
                'class' => 'backend\actions\controllers\proceso\CreateAction',
                'checkAccess' => [$this, 'checkAccess'],
                'workflowManager' => $this->workflowManager,
                'scenario' => 'register'


            ],
            'update' => [
                'class' => 'backend\actions\controllers\workflows\UpdateAction',
                'checkAccess' => [$this, 'checkAccess'],
                'workflowManager' => $this->workflowManager,
                'scenario' => 'update'


            ],
            'delete' => [
                'class' => 'backend\actions\controllers\workflows\DeleteAction',
                'checkAccess' => [$this, 'checkAccess'],
                'workflowManager'=>$this->workflowManager,

            ],

        ];
        $finalActions = array_merge($actions,$newActions);
        return $finalActions;
    }


}