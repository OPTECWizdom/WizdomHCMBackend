<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 23/10/2017
 * Time: 11:37
 */

namespace app\models;


use yii\db\ActiveRecord;

class VacacionEmpleadoMovimiento extends ActiveRecord
{

    public static function tableName()
    {
        return "vacaciones_empleado_movimiento ";
    }

    public static function primaryKey()
    {
        return [
                "compania","tipo_mov", "consecutivo_movimiento", "codigo_empleado",
                "consecutivo", "periodo", "regimen_vacaciones"
        ];
    }


    public function rules()
    {
        return [
            [
                ["compania","tipo_mov","consecutivo_movimiento","codigo_empleado",
                "consecutivo","periodo","regimen_vacaciones"],
                "required"
            ],
            [
                ["compania","tipo_mov","codigo_empleado","regimen_vacaciones",
                 "tstamp"],"string"
            ],
            [
                ["consecutivo_movimiento","consecutivo","periodo"],"integer"
            ],
            [
                ["dias_disponibles","dias_disfrutados","dias_disfrutado_movimiento"],
                "double"
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