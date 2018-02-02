<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 05/09/2017
 * Time: 8:26
 */

namespace backend\controllers;


use backend\rest\controllers\AbstractWizdomActiveController;

class EmpleadoToUserMigratorController extends AbstractWizdomActiveController
{
    private $workflowManager =  "EmpleadoToUserMigrator";
    public $modelClass = 'backend\models\security\securityUser\SecurityUser';


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