<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 22/01/2018
 * Time: 15:21
 */

namespace backend\pushNotifications;


interface INotificationPusher
{
    public function getType();

    public function getAction();

    public function getDestiny();

}