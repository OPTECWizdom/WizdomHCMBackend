<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 06/04/2018
 * Time: 15:43
 */

namespace backend\models\profesion;


use yii\db\ActiveRecord;

class Profesion extends ActiveRecord
{
    public static function tableName()
    {
        return "PROFESION";
    }

    public static function primaryKey()
    {
        return ['codigo_profesion'];
    }

}