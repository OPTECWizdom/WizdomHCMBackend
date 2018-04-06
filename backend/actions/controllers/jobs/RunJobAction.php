<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 01/09/2017
 * Time: 19:34
 */
namespace backend\actions\controllers\jobs;

use yii\rest\Action;
use Yii;
use yii\web\ServerErrorHttpException;



class RunJobAction extends Action
{



    public $workflowManager;

    public function run()
    {
        try {
            if ($this->checkAccess) {
                call_user_func($this->checkAccess, $this->id);
            }

            $workflowManagerFactory = new \backend\workflowManagers\WorkflowManagerFactory();
            $workflowManager = $workflowManagerFactory->createWorkflowManager($this->workflowManager, []);

            $result = $workflowManager->run();
            if ($result) {
                $response = Yii::$app->getResponse();
                $response->setStatusCode(201);
            } else {
                throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
            }
        }catch (\Exception $e){
            Yii::getLogger()->log($e->getMessage().' '.$e->getLine().$e->getFile().' ' .$e->getTraceAsString(),1);
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');

        }

        return null;
    }



}