<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 23/08/2017
 * Time: 12:34
 */

namespace backend\actions\controllers\workflows;

use Yii;
use yii\rest\Action;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;
use backend\workflowManagers;

class UpdateAction extends Action

{
    public $workflowManager;
    public $scenario = Model::SCENARIO_DEFAULT;


    public function run($id)
    {
        /* @var $model ActiveRecord */
        $model = $this->findModel($id);

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        $model->scenario = $this->scenario;
        $workflowManagerFactory = new workflowManagers\WorkflowManagerFactory();
        $paramsVacaciones = Yii::$app->getRequest()->getBodyParams()["movimiento_vacaciones"];
        $model->load($paramsVacaciones,'');
        $params = [];
        $params["modelMovimientoVacaciones"] = $model;
        $params["flujo_proceso"] = Yii::$app->getRequest()->getBodyParams()["flujo_proceso"];
        $workflowManager = $workflowManagerFactory->createWorkflowManager($this->workflowManager,['scenario'=>$this->scenario, 'params'=>$params]);

        $result = $workflowManager->run();
        if (!$result) {
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        }
        return $model;

    }


}