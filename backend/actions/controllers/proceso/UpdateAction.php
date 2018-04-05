<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 23/08/2017
 * Time: 12:34
 */

namespace backend\actions\controllers\proceso;

use backend\models\factories\WizdomModelFactory;
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
        $params = Yii::$app->getRequest()->getBodyParams();
        $this->modelClass  = WizdomModelFactory::getWizdomModel($params['flujo_proceso']['tipo_flujo_proceso']);
        /* @var $model ActiveRecord */
        $model = $this->findModel($id);

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        //$model->scenario = $this->scenario;
        $workflowManagerFactory = new workflowManagers\WorkflowManagerFactory();
        $model->load($params['model'],'');
        $params['model'] = $model;
        $workflowManager = $workflowManagerFactory->createWorkflowManager($this->workflowManager,['scenario'=>$this->scenario, 'params'=>$params]);
        $result = $workflowManager->run();
        if (!$result) {
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        }
        return $model;

    }


}