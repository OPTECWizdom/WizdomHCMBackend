<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 31/08/2017
 * Time: 10:06
 */

namespace app\models;


class FlujoProcesoNotificacionesHelper
{
    private $flujoProceso;
    private $tipoFlujoProceso;
    public $notificacionesTipoProceso;
    private $proceso;
    private $agentes;

    public function __construct(FlujoProceso $flujoProceso)
    {
        $this->flujoProceso = $flujoProceso;
        $this->getTipoProcesoFromFlujoProceso();
        $this->getNotificacionesFromTipoFlujoProceso();
        $this->getProcesoFromFlujoProceso();
        $this->agentes = array();

    }


    private function getTipoProcesoFromFlujoProceso(){

        $primaryKeys = FlujoTipoProceso::primaryKey();
        $flujoTipoProcesoPK = $this->flujoProceso->getAttributes($primaryKeys);
        $flujoTipoProceso = FlujoTipoProceso::find()->where($flujoTipoProcesoPK)->one();
        if(!empty($flujoTipoProceso)){
            $this->tipoFlujoProceso = $flujoTipoProceso;

        }


    }


    private function getNotificacionesFromTipoFlujoProceso(){
        $primaryKeysFlujoTipoProceso = FlujoTipoProceso::primaryKey();
        $flujoTipoProcesoPK = $this->flujoProceso->getAttributes($primaryKeysFlujoTipoProceso);
        $whereCondition = $flujoTipoProcesoPK;
        $whereCondition["estado"] = $this->flujoProceso->getAttribute("estado");
        $notificaciones = FlujoTipoProcesoNotificacion::find()->where($whereCondition)->all();
        $this->notificacionesTipoProceso = $notificaciones;

    }


    public function insertNotificaciones(){
        foreach($this->notificacionesTipoProceso as $notificacionTipoProceso)
        {
            $notificaciones = $this->getNotificacionFromNotificacionTipoProceso($notificacionTipoProceso);
            foreach ($notificaciones as $notificacion){
                if(!empty($notificacion)){
                    $notificacion->save();

                }
            }



        }
    }

    private function  getNotificacionFromNotificacionTipoProceso(FlujoTipoProcesoNotificacion $notificacionTipoProceso)
    {
        $notificaciones = array();
        $attributes = array();
        $attributes["compania"] = $notificacionTipoProceso->getAttribute("compania");
        $attributes["sistema_procedencia"] = "RHU";
        $attributes["mensaje"] = $notificacionTipoProceso->getAttribute("mensaje");
        $agenteType = $notificacionTipoProceso->getAttribute("agente");
        if(!array_key_exists($agenteType,$this->agentes)){
            $this->getAgenteOfNotificacion($agenteType);

        }
        $agentes = $this->agentes[$agenteType];
        foreach($agentes as $agente){
            $notificacion = $this->buildNotificacion($attributes,$agente);
            $notificaciones[] = $notificacion;

        }
        return $notificaciones;




    }

    private function getAgenteOfNotificacion(string $agente){
        $agenteSearcherFactory = new AgenteSearcherFactory($this->flujoProceso,$this->proceso);
        $agenteSearcher = $agenteSearcherFactory->createAgenteSearcher($agente);
        if(!empty($agenteSearcher)) {
            $agentes = $agenteSearcher->search();
            $this->agentes[$agente] = $agentes;
        }

    }

    private function getProcesoFromFlujoProceso(){
        $primaryKeysProceso = Proceso::primaryKey();
        $procesoPK = $this->flujoProceso->getAttributes($primaryKeysProceso);
        $proceso = Proceso::find()->where($procesoPK)->one();
        $this->proceso = $proceso;
    }


    private function buildNotificacion($attributes,Empleado $empleado){
        $notificacion = new Notificacion();
        $notificacion->setAttributes($attributes);
        $notificacion->setAttribute("codigo_empleado",$empleado->getAttribute("codigo_empleado"));
        $notificacion->setAttribute("empleado_destino",$empleado->getAttribute("codigo_empleado"));
        $notificacion->setAttribute("naturaleza_notificacion",'N');
        $notificacion->setAttribute("leido",0);
        return $notificacion;


    }



}