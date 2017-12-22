<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 22/12/2017
 * Time: 10:34
 */
namespace backend\rest\controllers;
class WizdomActiveController extends \yii\rest\ActiveController
{

    public function actions()
    {
        $actions = parent::actions();
        $newActions = [

            'create' => [
                'class' => 'backend\rest\actions\CreateAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->createScenario,
            ],

        ];
        return array_merge($actions,$newActions);
    }



}