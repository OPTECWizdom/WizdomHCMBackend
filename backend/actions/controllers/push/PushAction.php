<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 23/01/2018
 * Time: 10:21
 */


namespace backend\actions\controllers\push;
use backend\pushNotifications\PushNotification;
use yii\rest\Action;
use Yii;
use yii\web\ServerErrorHttpException;

class PushAction extends Action
{
    public $modelClass;



    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /**
         * @var PushNotification $model
         */
        $model = new $this->modelClass();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        $response = Yii::$app->getResponse();
        $response->setStatusCode(201);
        try {
            $model->sendPushNotification();
        }catch (\Exception $e)
        {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        return $model;


    }

}