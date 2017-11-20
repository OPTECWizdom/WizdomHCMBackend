<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 20/10/2017
 * Time: 15:06
 */

namespace backend\models;


use yii\db\ActiveRecord;

class ControlAjusteVacacionAcumulado extends ActiveRecord
{
    public static function tableName()
    {
        return "CONTROL_AJUSTE_VACACION_MOV";

    }

    public static function primaryKey()
    {
        return ["compania","codigo_empleado"];
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
    public function rules()
    {
        return
        [
            [
                ["compania","codigo_empleado"],"required"
            ],
            [
                ["compania","codigo_empleado","tstamp"],"string"
            ],
            [
                ["dias_ajuste"],"double"
            ]

        ];
    }

}