<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 31/08/2017
 * Time: 12:54
 */

namespace backend\behaviors;
use Yii;


class CustomAutoNumber extends \yii\behaviors\AttributeBehavior
{

    public $model;
    public $column;


    public function getValue($event)
    {
        $max = Yii::$app->db->createCommand("SELECT MAX($this->column) FROM $this->model")->queryScalar();
        if(!empty($max)){
            return $max+1;
        }
        else{
            return 1;
        }

    }


}