<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 20/10/2017
 * Time: 15:13
 */

namespace backend\models;


use yii\db\ActiveRecord;

class ControlAjusteVacacionesMovimiento extends ActiveRecord
{
    public static function tableName()
    {
        return "CONTROL_AJUSTE_VACACIONS_MOV";
    }
    public static function primaryKey()
    {
        return ["compania","tipo_mov","consecutivo_movimiento"];
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
        return [
            [
                ["compania","tipo_mov","consecutivo_movimiento"],"required"
            ],
            [
                ["compania","tipo_mov","emp_compania","codigo_empleado","tstamp"],"string"
            ],
            [
                [   "consecutivo_movimiento"

                ],"integer"
            ],
            [
                ["dias_ajuste"],"double"
            ]

        ];

    }

    /**\
     * @return \yii\db\ActiveQuery
     */

    public function getControlAjusteAcumulado()
    {

        return $this->hasOne(ControlAjusteVacacionAcumulado::className(),["compania"=>"compania","codigo_empleado"=>"codigo_empleado"]);
    }


}