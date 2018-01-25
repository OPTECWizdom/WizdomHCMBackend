<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 25/01/2018
 * Time: 15:22
 */

namespace backend\controllers;


use backend\rest\controllers\AbstractWizdomPushNotificationsController;

class MessagePushController extends AbstractWizdomPushNotificationsController
{
    public $pushNotificationModel = 'backend\pushNotifications\MessagePushNotification';


}