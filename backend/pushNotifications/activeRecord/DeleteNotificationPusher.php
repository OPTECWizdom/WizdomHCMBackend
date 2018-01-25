<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 25/01/2018
 * Time: 9:41
 */

namespace backend\pushNotifications\activeRecord;


use backend\pushNotifications\BasePushNotification;
use yii\db\ActiveRecord;


class DeleteNotificationPusher extends ActiveRecordNotificationPusher
{



    public function attachEvents(ActiveRecord $model)
    {
        $this->model = $model;
        $model = $this->model;
        $model->on($model::EVENT_AFTER_DELETE, [$this, "sendNotificationPush"]);
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
        $notification->setAttributes(["task" => $model->getPushNotificationTask(),
            "action" => 'remove',
            "title"=>$model->getPushNotificationTitle(),
            "message" => $model->getDeletedPushNotificationMessage(),
            "destinies" => $model->getPushNotificationDestinies()]);
        return $notification;
    }
}


