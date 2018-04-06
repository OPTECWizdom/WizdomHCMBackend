<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 06/04/2018
 * Time: 10:51
 */

namespace backend\models\idioma;


use yii\db\ActiveRecord;

class Idioma extends ActiveRecord
{
    public static function tableName()
    {
        return "IDIOMA";
    }


    public static function primaryKey()
    {
        return ["idioma"];
    }

}