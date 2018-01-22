<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 22/01/2018
 * Time: 16:17
 */

namespace backend\pushNotifications\activeRecord;


use backend\pushNotifications\NotificationPusher;

abstract class ActiveRecordNotificationPusher extends NotificationPusher
{



    /**
     * @var AbstractNotificationPusherObject
     */
    protected $model;

    public function __construct(array $config = [],AbstractNotificationPusherObject $model)
    {
        parent::__construct($config);
        $this->model = $model;
    }

    public  function init()
    {
        parent::init();
        $this->attachEvents();
    }

    public abstract function attachEvents();
}