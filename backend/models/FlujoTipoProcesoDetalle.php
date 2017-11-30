<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 30/11/2017
 * Time: 15:03
 */

namespace backend\models;


use yii\db\ActiveRecord;

class FlujoTipoProcesoDetalle extends ActiveRecord
{
    /**
     * @var ProcesoDetalle $proceso
     */
    private $proceso;

    /**
     * @return ProcesoDetalle
     */
    public function getProceso(): ProcesoDetalle
    {
        return $this->proceso;
    }

    /**
     * @param ProcesoDetalle $proceso
     */
    public function setProceso(ProcesoDetalle $proceso)
    {
        $this->proceso = $proceso;
    }

    public function getTipoFlujoProcesoAgenteDetalle()
    {
        /**
         * @var FlujoTipoProcesoAgenteDetalle[] $agentes
         */
        $agentes = $this->hasMany(FlujoTipoProcesoAgenteDetalle::className(),
                                                        ["compania"=>"compania","tipo_flujo_proceso"=>"tipo_flujo_proceso",
                                                         "codigo_tarea"=>"codigo_tarea"])->all();
        foreach ($agentes as &$agente)
        {
            $agente->setProceso($this->proceso);
        }
        return $agentes;

    }



    public static function tableName()
    {
        return "FLUJO_TIPO_PROCESO";
    }

    public static function primaryKey()
    {
        return ["compania","tipo_flujo_proceso","codigo_tarea"];
    }

    public function fields()
    {
        $parentFields = parent::fields();
        $customFields = ["tipoFlujoProcesoAgenteDetalle"];
        return array_merge($parentFields,$customFields);
    }


}