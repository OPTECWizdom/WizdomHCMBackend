<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 24/08/2017
 * Time: 11:38
 */
namespace backend\behaviors;
use \yii\behaviors\TimestampBehavior;
use Yii;

class TimestampStringBehavior extends TimestampBehavior
{
    protected function getValue($event)
    {
        if ($this->value === null) {
            $dateTime = Yii::$app->formatter->asDatetime(time());
            return $dateTime;
        }
        return parent::getValue($event);
    }

}