<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 22/08/2017
 * Time: 17:16
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Proceso extends ActiveRecord
{
    /**
     * @return \yii\db\ActiveQuery
     */

    public function getEmpleadoSolicitante()
    {
        return $this->hasOne(Empleado::className(),["compania"=>"compania","codigo_empleado"=>"codigo_empleado"]);
    }


    public static function tableName()
    {
        return 'PROCESO';
    }

    public static function primaryKey()
    {
        return ['compania','tipo_flujo_proceso','id_proceso'];
    }



    public function rules()
    {
        return
                [
                    [
                        [
                            "compania","tipo_flujo_proceso",
                            "codigo_empleado","sistema_procedencia"
                        ],
                        "required","on"=>['register']
                    ],
                    [
                      [
                          "compania","tipo_flujo_proceso",
                          "codigo_empleado","sistema_procedencia"
                      ],"string"
                    ],
                    [
                        ["id_proceso"],"integer"
                    ],
                    [
                        ["fecha_creacion","tstamp"],"string"
                    ]

                ];

    }
    public function behaviors()
    {
        return
            [
                'timestamp' => [
                    'class' => 'backend\behaviors\TimestampStringBehavior',
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['fecha_creacion', 'tstamp'],
                    ],
                ],
                [

                    'class' => 'common\components\mdmsoft\autonumber\Behavior',
                    'attribute' => 'id_proceso', // required

                ]
        ];
    }





}