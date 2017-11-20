<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 25/08/2017
 * Time: 12:36
 */

namespace backend\models;


use yii\db\ActiveRecord;

class FlujoProcesoAgente extends ActiveRecord
{

    public static function tableName()
    {
        return "FLUJO_PROCESO_AGENTE";
    }
    public static function primaryKey()
    {
        return [
            "compania","id_proceso","tipo_flujo_proceso","codigo_tarea","consecutivo"
        ];
    }


    public function rules()
    {
        return [
            [
                ["compania","id_proceso","tipo_flujo_proceso","codigo_tarea","consecutivo"],"required"

            ],
            [
                ["agente","parametro_agente","tstamp","correo_enviado","fecha_creacion"],
                "string"
            ],
            [
                ["tiempo_espera"],
                "integer"
            ],


        ];
    }


    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'backend\behaviors\TimestampStringBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['fecha_creacion', 'tstamp'],
                ]
            ],

        ];

    }
}