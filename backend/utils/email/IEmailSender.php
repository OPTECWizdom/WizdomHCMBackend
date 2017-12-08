<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 07/12/2017
 * Time: 10:46
 */

namespace  backend\utils\email;
;


interface IEmailSender
{
    /**
     * @param IEmailable $emailObject
     * @return bool
     */

    public function sendEmail(IEmailable $emailObject);

}