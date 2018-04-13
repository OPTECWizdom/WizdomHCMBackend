<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 23/08/2017
 * Time: 12:34
 */

namespace backend\actions\controllers\proceso;
use backend\models\factories\WizdomModelFactory;
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
        $tableName = explode(',',$id)[0];
        $this->modelClass  = WizdomModelFactory::getWizdomModel($tableName);
        $id = implode(',',array_slice(explode(',',$id),1));
        $model = $this->findModel($id);
        Yii::$app->getResponse();
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }
        $params = [];
        $params["model"] = $model;
        $workflowManagerFactory = new workflowManagers\WorkflowManagerFactory();
        $workflowManager = $workflowManagerFactory->createWorkflowManager($this->workflowManager,['scenario'=>'delete', 'params'=>$params]);
        $result = $workflowManager->run();
        if ($result === false) {
            Yii::$app->getResponse()->setStatusCode(500);
            throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');

        }
        Yii::$app->getResponse()->setStatusCode(204);
    }
}