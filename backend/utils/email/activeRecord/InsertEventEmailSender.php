<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 26/01/2018
 * Time: 15:22
 */

namespace backend\utils\email\activeRecord;



class InsertEventEmailSender extends AbstractEventEmailSender
{

    public function attachEvents(AbstractActiveRecordEmailSender $model)
    {
       $this->model = $model;
       $model->on($model::EVENT_AFTER_INSERT,[$this,"sendEmail"]);
    }
}