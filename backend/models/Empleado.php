<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 31/08/2017
 * Time: 12:27
 */

namespace app\models;


use yii\db\ActiveRecord;

class Empleado extends  ActiveRecord
{

    public static function tableName()
    {
        return "empleado";
    }

    public static function primaryKey()
    {
        return [
            "compania","codigo_empleado"
        ];
    }

    public function rules()
    {
        return [
            [
                [
                    "compania","codigo_empleado"
                ],'required'
            ],
            [
                [
                    "nombre","primer_apellido","segundo_apellido",
                    "codigo_nodo_organigrama","codigo_puesto",
                    "codigo_jefe"
                ],"string"
            ]


        ];
    }


}