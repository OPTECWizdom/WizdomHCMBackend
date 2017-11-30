<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 30/11/2017
 * Time: 10:34
 */

namespace backend\models;


use yii\db\ActiveRecord;

class FlujoProcesoAgenteDetalle extends ActiveRecord
{
    /**
     * @var ProcesoDetalle $procesoDetalle;
     */
    private $procesoDetalle;

    /**
     * @return ProcesoDetalle
     */

    /**
     * @var FlujoProcesoDetalle $flujoProcesoDetalle;
     */
    private $flujoProcesoDetalle;

    /**
     * @param FlujoProcesoDetalle $flujoProcesoDetalle
     */

    public function setFlujoProcesoDetalle($flujoProcesoDetalle)
    {
        $this->flujoProcesoDetalle = $flujoProcesoDetalle;
    }

    /**
     * @return FlujoProcesoDetalle
     */

    public function getFlujoProcesoDetalle()
    {
        return $this->flujoProcesoDetalle;
    }



    public function getProcesoDetalle()
    {
        return $this->procesoDetalle;
    }

    /**
     * @param ProcesoDetalle $procesoDetalle
     */

    public function setProcesoDetalle($procesoDetalle)
    {
        $this->procesoDetalle = $procesoDetalle;
    }

    public static function tableName()
    {
        return 'FLUJO_PROCESO_AGENTE';
    }

    public static function primaryKey()
    {
        return ["compania","id_proceso","tipo_flujo_proceso","codigo_tarea","consecutivo"];
    }

    /**
     * @return array|Empleado[]|null
     */
    public function getPosiblesEjecutante()
    {
        $flujoProceso = $this->getFlujoProceso();
        $proceso = $this->getProceso();
        $agenteSearcherFactory = new AgenteSearcherFactory($flujoProceso,$proceso,$this->parametro_agente);
        $agenteSearcher = $agenteSearcherFactory->createAgenteSearcher($this->agente);
        if(!empty($agenteSearcher))
        {
            return $agenteSearcher->search();
        }
        return [];

    }

    /**
     * @return array|Empleado[]|null
     */
    public function getEjecutantes()
    {

       $flujoProceso = $this->getFlujoProceso();
       $codigoEmpleadoEjecutante = $flujoProceso->codigo_empleado_ejecutante;
       if(!empty($codigoEmpleadoEjecutante))
       {
           return Empleado::find()->where(["compania"=>$flujoProceso->compania,"codigo_empleado"=>$codigoEmpleadoEjecutante])->all();
       }
       else{
           return $this->getPosiblesEjecutante();
       }

    }


    private function getFlujoProceso()
    {

        $flujoProceso = new FlujoProceso();
        if(!empty($this->flujoProcesoDetalle))
        {
            $flujoProceso->setAttributes($this->flujoProcesoDetalle->getAttributes());
        }
        return $flujoProceso;
    }



    private function getProceso()
    {
        $proceso = new Proceso();
        if(!empty($this->procesoDetalle))
        {
            $proceso->setAttributes($this->procesoDetalle->getAttributes());

        }
        return $proceso;
    }



    public function fields()
    {
        $parentFields = parent::fields();
        $customFields = ['ejecutantes'];
        return array_merge($parentFields,$customFields);
    }


}