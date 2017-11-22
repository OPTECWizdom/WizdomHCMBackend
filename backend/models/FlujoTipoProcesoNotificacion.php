<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 31/08/2017
 * Time: 10:17
 */

namespace backend\models;


use yii\db\ActiveRecord;

class FlujoTipoProcesoNotificacion extends ActiveRecord
{

    public static function tableName()
    {
        return "FLUJO_TIPO_PROCESO_NOTIFICACIO";
    }

    public static function primaryKey()
    {
        return [
            "compania","tipo_flujo_proceso","codigo_tarea","consecutivo"
        ];
    }

    public function rules()
    {
        return [
            [
                [
                    "compania","tipo_flujo_proceso","codigo_tarea","consecutivo"
                ],"required"
            ],
            [
                [
                    "agente","estado","tstamp","mensaje"
                ],"string"
            ]
        ];
    }

}