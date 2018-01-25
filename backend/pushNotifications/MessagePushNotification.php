<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 25/01/2018
 * Time: 15:14
 */

namespace backend\pushNotifications;


class MessagePushNotification extends PushNotification
{
    public function __construct()
    {
        parent::__construct();
        $this->setAttributes(['task'=>'mensaje',
                             "action"=>"add",
                             "title"=>\Yii::t("app","nuevoMensaje"),
                             ]);
    }


}