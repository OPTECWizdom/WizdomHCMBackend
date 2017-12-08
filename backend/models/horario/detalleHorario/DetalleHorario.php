<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 19/10/2017
 * Time: 14:30
 */

namespace backend\models\horario\detalleHorario;


use yii\db\ActiveRecord;

class DetalleHorario extends ActiveRecord
{

    public static function tableName()
    {
        return "DETALLE_HORARIO";
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