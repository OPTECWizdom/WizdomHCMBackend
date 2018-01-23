<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 22/01/2018
 * Time: 16:19
 */

namespace backend\pushNotifications\activeRecord;


use backend\pushNotifications\BasePushNotification;

class InsertNotificationPusher extends ActiveRecordNotificationPusher
{

    public function __construct(array $config = [], AbstractNotificationPusherObject $model)
    {
        parent::__construct($config, $model);
    }

    public function attachEvents()
    {
        $model = $this->model;
        $model->on($model::EVENT_AFTER_INSERT,[$this,"sendNotificationPush"]);
        \Yii::info("pruebaaa","app");
    }

    public function sendNotificationPush()
    {
        $notificationPush = $this->getNotificationPush();
        $notificationPush->sendPushNotification();
    }

    public function getNotificationPush()
    {
        $model = $this->model;
        $notification = new BasePushNotification();
        $notification->setTask($model->getPushNotificationTask());
        $notification->setAction('add');
        $notification->setMessage($model->getCreatedPushNotificationMessage());
        return $notification;
    }


}