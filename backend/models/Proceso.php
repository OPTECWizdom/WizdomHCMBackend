<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 22/08/2017
 * Time: 17:16
 */

namespace app\models;


use yii\db\ActiveRecord;

class Proceso extends ActiveRecord
{


    public static function tableName()
    {
        return 'proceso';
    }

    public static function primaryKey()
    {
        return ['compania','tipo_flujo_proceso','id_proceso'];
    }

}