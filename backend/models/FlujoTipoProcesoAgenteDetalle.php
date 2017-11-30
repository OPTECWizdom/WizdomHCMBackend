<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 30/11/2017
 * Time: 16:03
 */

namespace backend\models;


use yii\db\ActiveRecord;

class FlujoTipoProcesoAgenteDetalle extends ActiveRecord
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



    public static function tableName()
    {
        return "FLUJO_TIPO_PROCESO_AGENTE";
    }

    public static function primaryKey()
    {
        return [
            "compania","tipo_flujo_proceso",
            "codigo_tarea","consecutivo"
        ];
    }

    /**
     * @return array|Empleado[]|null
     */

    public function getEjecutantes()
    {
        $proceso = new Proceso();
        $proceso->setAttributes($this->getProceso()->getAttributes());
        $agenteSearcherFactory = new AgenteSearcherFactory(new FlujoProceso(),$proceso,$this->parametro_agente);
        $agenteSearcher = $agenteSearcherFactory->createAgenteSearcher($this->agente);
        if(!empty($agenteSearcher))
        {
            return $agenteSearcher->search();
        }
        return [];

    }

    public function fields()
    {
        $parentFields =  parent::fields(); // TODO: Change the autogenerated stub
        $customFields = ["ejecutantes"];
        return array_merge($parentFields,$customFields);
    }


}