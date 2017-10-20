<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 19/10/2017
 * Time: 10:26
 */

namespace app\models;


use yii\db\ActiveRecord;

class VacacionesEmpleadoMovimiento extends ActiveRecord
{

    public static function tableName()
    {
        return "vacaciones_empleado_movimiento";
    }

    public static  function primaryKey()
    {
        return ["compania","tipo_mov","consecutivo_movimiento","codigo_empleado","periodo","consecutivo"];
    }

    public function rules()
    {
        return [
            [
                ["compania","tipo_mov","consecutivo_movimiento","codigo_empleado","periodo","consecutivo"],
                "required"

            ],
            [
                ["consecutivo","consecutivo_movimiento","periodo"],"integer"
            ],
            [
                ["compania","tipo_mov","codigo_empleado",
                    "tstamp","regimen_vacaciones"],
                "string"
            ],
            [
                ["dias_disponibles","dias_disfrutados","dias_disfrutados_movimiento"],"double"


            ]



        ];

    }
    public function behaviors()
    {
        return [

            'timestamp' => [
                'class' => 'backend\behaviors\TimestampStringBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['tstamp'],
                ]
            ]

        ];

    }

}