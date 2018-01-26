<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 22/01/2018
 * Time: 16:17
 */

namespace backend\pushNotifications\activeRecord;


use backend\pushNotifications\NotificationPusher;
use yii\db\ActiveRecord;

abstract class ActiveRecordNotificationPusher extends NotificationPusher
{



    /**
     * @var AbstractNotificationPusherObject
     */
    public $model;


    public abstract function attachEvents(ActiveRecord $model);


}