<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 25/08/2017
 * Time: 12:33
 */

namespace backend\models\proceso\flujoTipoProceso\flujoTipoProcesoAgente;
use yii\db\ActiveRecord;
use backend\models\proceso\flujoProceso\FlujoProceso;

class FlujoTipoProcesoAgente extends ActiveRecord
{

    public static function tableName()
    {
        return "FLUJO_TIPO_PROCESO_AGENTE";
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
                ["compania,tipo_flujo_proceso", "codigo_tarea", "consecutivo"],
                "required"
            ],
            [
                ["prioridad", "tiempo_espera"], "integer"

            ],
            [
                ["agente", "parametro_agente", "tstamp"], "string"
            ]

        ];
    }

    public static function findAgentesOfFlujoProceso(FlujoProceso $flujoProceso){
        $compania = $flujoProceso->getAttribute('compania');
        $flujoTipoProceso = $flujoProceso->getAttribute('tipo_flujo_proceso');
        $codigoTarea = $flujoProceso->getAttribute('codigo_tarea');
        $flujoProcesoAgentes = FlujoTipoProcesoAgente::find()
                                ->where(["compania"=>$compania,
                                        "tipo_flujo_proceso"=>$flujoTipoProceso,
                                         "codigo_tarea"=>$codigoTarea])
                                ->orderBy(
                                    'prioridad'
                                )->all();
        return $flujoProcesoAgentes;

    }

}