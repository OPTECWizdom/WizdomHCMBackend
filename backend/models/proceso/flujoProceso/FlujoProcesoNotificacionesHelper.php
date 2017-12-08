<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 31/08/2017
 * Time: 10:06
 */

namespace backend\models\proceso\flujoProceso;


use backend\models\proceso\flujoTipoProceso\FlujoTipoProceso;
use backend\models\proceso\flujoTipoProceso\flujoTipoProcesoNotificacion\FlujoTipoProcesoNotificacion;
use backend\utils\agenteSearcher\AgenteSearcherFactory;
use backend\models\proceso\Proceso;
use backend\models\notificacion\Notificacion;
use backend\models\empleado\Empleado;
use backend\models\procesoModelConnector\FactoryProcesoSubjectConnector;
use backend\models\procesoModelConnector\IProcesoSubject;

class FlujoProcesoNotificacionesHelper
{
    private $flujoProceso;
    private $tipoFlujoProceso;
    public $notificacionesTipoProceso;
    private $proceso;
    private $agentes;
    private $procesoSubject;

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
        $attributes["asunto"] = $this->getAsuntoNotificacion();
        $attributes["mensaje"] = $this->getDescripcionProcesoSubject()." : ".$notificacionTipoProceso->getAttribute("mensaje");
        $agenteType = $notificacionTipoProceso->getAttribute("agente");
        if(!array_key_exists($agenteType,$this->agentes)){
            $this->getAgenteOfNotificacion($agenteType,$notificacionTipoProceso);

        }
        $agentes = $this->agentes[$agenteType];
        foreach($agentes as $agente){
            $notificacion = $this->buildNotificacion($attributes,$agente);
            $notificaciones[] = $notificacion;

        }
        return $notificaciones;




    }

    private function getAgenteOfNotificacion(string $agente,FlujoTipoProcesoNotificacion $flujoTipoProcesoNotificacion){
        $parametroAgente = $flujoTipoProcesoNotificacion->getAttribute("parametro_agente");
        $agenteSearcherFactory = new AgenteSearcherFactory($this->proceso,$parametroAgente);
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
        $notificacion->setAttribute("empleado_envia",$empleado->getAttribute("codigo_empleado"));
        $notificacion->setAttribute("empleado_destino",$empleado->getAttribute("codigo_empleado"));
        $notificacion->setAttribute("naturaleza_notificacion",'N');
        $notificacion->setAttribute("leido",0);
        return $notificacion;


    }

    /**
     * @return IProcesoSubject
     */

    private function getProcesoSubject()
    {
        if(empty($this->procesoSubject)) {
            $factory = new FactoryProcesoSubjectConnector();
            $connector = $factory->getSubjectProceso($this->proceso);
            if (!empty($connector)) {
                $subject = $connector->getProcesoSubject();
                if (!empty($subject)) {
                     $this->procesoSubject = $subject;
                }

            }
        }

        return $this->procesoSubject;
    }


    private function getAsuntoNotificacion()
    {
        $subjectText = "Wizdom HCM - Notificación";
        $subject = $this->getProcesoSubject();
        if(!empty($subject))
        {
            $subjectText.=" - ".$subject->getNotificationSubject();
        }
        return $subjectText;
    }

    private function getDescripcionProcesoSubject()
    {
        $description = "";
        $subject = $this->getProcesoSubject();
        if(!empty($subject))
        {
            $description .=$subject->getSubjectProcesoDescription();
        }
        return $description;
    }




}