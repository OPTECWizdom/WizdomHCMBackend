<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 23/01/2018
 * Time: 10:25
 */

namespace backend\pushNotifications;


class LogoutPushNotification extends PushNotification
{
    public function __construct()
    {
        parent::__construct();
        $this->setAttributes(['task'=>'logout']);
    }




}