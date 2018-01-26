<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 26/01/2018
 * Time: 15:22
 */

namespace backend\utils\email\activeRecord;


use backend\utils\email\GenericEmailSender;
use yii\base\Event;
use yii\db\ActiveRecord;

abstract class AbstractEventEmailSender extends Event
{

    /**
     * @var AbstractActiveRecordEmailSender
     */
    public $model;


    public abstract function attachEvents(AbstractActiveRecordEmailSender $model);


    public function sendEmail()
    {
        $emailer = new GenericEmailSender();
        $emailer->sendEmail($this->model);
    }

}