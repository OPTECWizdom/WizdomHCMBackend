<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 19/10/2017
 * Time: 14:30
 */

namespace app\models;


use yii\db\ActiveRecord;

class DetalleHorario extends ActiveRecord
{

    public static function tableName()
    {
        return "detalle_horario";
    }


    public static function primaryKey()
    {
        return ["compania","codigo_horario","dia_semana"];
    }

    public function rules()
    {
        return [
            [
                ["compania","codigo_horario","dia_semana"],"required"
            ]
        ];

    }

}