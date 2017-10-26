<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 23/08/2017
 * Time: 12:34
 */

namespace backend\actions\controllers\workflows;
use yii\rest\Action;
use Yii;
use yii\base\Model;
use backend\workflowManagers;
use backend\workflowManagers\AbstractWorkflowManager;
use yii\web\ServerErrorHttpException;



class DeleteAction extends Action
{
    /**
     * @var AbstractWorkflowManager $workflowManager ;
     */
    public $workflowManager;

    /**
     * Deletes a model.
     * @param mixed $id id of the model to be deleted.
     * @throws ServerErrorHttpException on failure.
     */
    public function run($id)
    {
        $model = $this->findModel($id);

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }
        $params = [];
        $params["modelMovimientoVacaciones"] = $model;
        $workflowManagerFactory = new workflowManagers\WorkflowManagerFactory();
        $workflowManager = $workflowManagerFactory->createWorkflowManager($this->workflowManager,['scenario'=>'delete', 'params'=>$params]);
        $result = $workflowManager->run();
        if ($result === false) {
            throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
        }

        Yii::$app->getResponse()->setStatusCode(204);
    }
}