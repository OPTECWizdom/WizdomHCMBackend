<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 22/01/2018
 * Time: 16:28
 */

namespace backend\pushNotifications\activeRecord;


use yii\db\ActiveRecord;

abstract class AbstractNotificationPusherObject extends ActiveRecord
{

    public abstract  function getPushNotificationTitle();

    public abstract function getPushNotificationDestinies();

    public abstract function getCreatedPushNotificationMessage();

    public abstract function getUpdatedPushNotificationMessage();

    public abstract function getDeletedPushNotificationMessage();

    public abstract function getPushNotificationDefaultMessage();

    public abstract function getPushNotificationTask();

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $this->attachNotificationsPusher();
    }

    public abstract function attachNotificationsPusher();
}