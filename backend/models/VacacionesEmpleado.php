<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 25/09/2017
 * Time: 16:57
 */

namespace app\models;


use yii\db\ActiveRecord;

class VacacionesEmpleado extends ActiveRecord
{

    public static function tableName()
    {
        return "vacaciones_empleado";
    }

    public static function primaryKey()
    {
        return ["compania","codigo_empleado","periodo","consecutivo","regimen_vacaciones"];
    }



}