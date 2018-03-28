<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 28/03/2018
 * Time: 15:08
 */

namespace backend\models\proceso\flujoTipoProceso\flujoTipoProcesoEstado;


use yii\db\ActiveRecord;

class FlujoTipoProcesoEstado extends ActiveRecord
{


    public static function tableName()
    {
        return "FLUJO_TIPO_PROCESO_ESTADOS";
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
                    "estado_flujo_proceso","columna_proceso_objeto","valor_columna_proceso_objeto","tstamp"
                ],"string"
            ]
        ];
    }

}
