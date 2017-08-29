<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 23/08/2017
 * Time: 12:27
 */

namespace backend\actions\controllers\workflows;


use yii\rest\Action;
use Yii;
use yii\base\Model;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;
use backend\workflowManagers;
use backend\workflowManagers\AbstractWorkflowManager;

class CreateAction extends Action
{
    /**
     * @var string the scenario to be assigned to the new model before it is validated and saved.
     */
    public $scenario = Model::SCENARIO_DEFAULT;
    /**
     * @var string the name of the view action. This property is need to create the URL when the model is successfully created.
     */
    public $viewAction = 'view';

    public $workflowManager;

    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /* @var $model \yii\db\ActiveRecord */

        $workflowManagerFactory = new workflowManagers\WorkflowManagerFactory();
        $params = Yii::$app->getRequest()->getBodyParams();
        $workflowManager = $workflowManagerFactory->createWorkflowManager($this->workflowManager,['scenario'=>$this->scenario,
                                                                                                 'params'=>$params]);
        $model = new $this->modelClass();

        $result = $workflowManager->run();
        if ($result) {
            $model->load(Yii::$app->getRequest()->getBodyParams(), '');
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $model = $workflowManager->getMovimientoVacaciones();
            $id = implode(',', array_values($model->getPrimaryKey(true)));
            $response->getHeaders()->set('Location', Url::toRoute([$this->viewAction, 'id' => $id], true));
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }

        return $model;
    }

}