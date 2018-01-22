<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 22/01/2018
 * Time: 14:58
 */

namespace backend\pushNotifications;


use yii\base\Component;

abstract class NotificationPusher extends Component
{

    public abstract function sendNotificationPush();


}