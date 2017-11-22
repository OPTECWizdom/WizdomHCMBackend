<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 31/08/2017
 * Time: 10:28
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Notificacion extends ActiveRecord
{

    public static function tableName()
    {
        return "NOTIFICACIONES";
    }

    public static function primaryKey()
    {
        return ["compania","consecutivo"];
    }
    public function behaviors()
    {
        return [
            [

                'class' => 'backend\behaviors\CustomAutoNumber',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['consecutivo'],
                ],
                "model"=>Notificacion::tableName(),
                "column" => "consecutivo"


            ],
            'timestamp' => [
                'class' => 'backend\behaviors\TimestampStringBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['fecha', 'tstamp'],
                ]
            ]

        ];

    }

    public function rules()
    {

        return [
            [
                [
                    "compania"
                ],"required"

            ],
            [
                [
                    "empleado_envia",
                    "empleado_destino","sistema_procedencia","fecha","asunto",
                    "tstamp","mensaje","naturaleza_notificacion"
                ],"string"
            ],
            [
                [
                    "leido","consecutivo"
                ],"integer"
            ]
        ];
    }

}