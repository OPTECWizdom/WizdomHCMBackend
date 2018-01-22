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


    public abstract function getPushNotificationDestiny();

    public abstract function getCreatedPushNotificationMessage();

    public abstract function getUpdatedPushNotificationMessage();

    public abstract function getDeletedPushNotificationMessage();

    public abstract function getPushNotificationDefaultMessage();

    public abstract function getPushNotificationTask();

    public  function attachNotificationsPusher()
    {
         new InsertNotificationPusher($this);

    }
}