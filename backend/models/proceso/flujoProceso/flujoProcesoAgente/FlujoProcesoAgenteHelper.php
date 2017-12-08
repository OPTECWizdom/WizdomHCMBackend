<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 25/08/2017
 * Time: 12:40
 */

namespace backend\models\proceso\flujoProceso\flujoProcesoAgente;



use backend\models\proceso\flujoProceso\FlujoProceso;
use backend\models\proceso\flujoTipoProceso\flujoTipoProcesoAgente\FlujoTipoProcesoAgente;

class FlujoProcesoAgenteHelper implements IFlujoProcesoAgenteHelper
{
    private $flujoProceso;


    public function __construct(FlujoProceso $flujoProceso)
    {
        $this->flujoProceso = $flujoProceso;
    }

    public function insertAgente()
    {
        $flujoProcesoAgente = $this->getFirstAgenteFlujoProceso();
        if(!empty($flujoProcesoAgente)){
            $flujoProcesoAgente->save();

        }



    }




    public function getFirstAgenteFlujoProceso(){
        $flujoTipoProcesoAgentes = FlujoTipoProcesoAgente::findAgentesOfFlujoProceso($this->flujoProceso);
        if (!empty($flujoTipoProcesoAgentes)){
            $flujoTipoProcesoAgente = $flujoTipoProcesoAgentes[0];
            return $this->getFlujoProcesoAgenteFromFlujoTipoProcesoAgente($flujoTipoProcesoAgente);

        }


    }

    public function getFlujoProcesoAgenteFromFlujoTipoProcesoAgente(FlujoTipoProcesoAgente $flujoTipoProcesoAgente){
        $flujoProcesoAgente = new FlujoProcesoAgente();
        $flujoProcesoAgente->setAttributes($flujoTipoProcesoAgente->getAttributes());
        $flujoProcesoAgente->setAttribute('id_proceso',$this->flujoProceso->getAttribute('id_proceso'));
        $flujoProcesoAgente->setAttribute('correo_enviado','N');
        return $flujoProcesoAgente;

    }


    public function updateAgentes(){
        $agenteFlujoTipoProceso = $this->getNextAgenteFlujoTipoProceso();
        if(!empty($agenteFlujoTipoProceso)){
            $agenteFlujoProceso = $this->getFlujoProcesoAgenteFromFlujoTipoProcesoAgente($agenteFlujoTipoProceso);
            $agenteFlujoProceso->save();
        }

    }

    private function getInsertedAgentesOfFlujoProceso(){
        $ids = FlujoProceso::primaryKey();
        $insertedAgentes = FlujoProcesoAgente::find()->where($this->flujoProceso->getAttributes($ids))->orderBy("fecha_creacion")->all();
        return $insertedAgentes;
    }

    private function getAgentesOfFlujoTipoProceso(){
        $flujoTipoProcesoAgentes = FlujoTipoProcesoAgente::findAgentesOfFlujoProceso($this->flujoProceso);
        return $flujoTipoProcesoAgentes;
    }


    private function getNextAgenteFlujoTipoProceso()
    {
        $agentesProceso = $this->getInsertedAgentesOfFlujoProceso();
        $agentesTipoProceso = $this->getAgentesOfFlujoTipoProceso();
        if(!empty($agentesProceso) || !empty($agentesTipoProceso)){
            $agentesProcesoLen = sizeof($agentesProceso);
            $agentesTipoProcesoLen = sizeof($agentesTipoProceso);
            if($agentesProcesoLen<$agentesTipoProcesoLen){
                $agenteProceso = $agentesProceso[$agentesProcesoLen-1];
                $agenteTipoProceso = $agentesTipoProceso[$agentesProcesoLen-1];
                $fechaInvalida = $this->evalAgenteWaitingTime($agenteProceso,$agenteTipoProceso);
                if($fechaInvalida){
                    return  $agentesTipoProceso[$agentesProcesoLen];
                }

            }

        }
        return null;
    }


    private function evalAgenteWaitingTime(FlujoProcesoAgente $flujoProcesoAgente,FlujoTipoProcesoAgente $flujoTipoProcesoAgente)
    {
        $diasLimite = $flujoTipoProcesoAgente->getAttribute("tiempo_espera");
        $fechaLimite = new \DateTime($flujoProcesoAgente->getAttribute("fecha_creacion"));
        $fechaLimite->modify("+$diasLimite day");
        $fechaHoy = new \DateTime("now");
        $fechaHoy->setTime(0,0);
        if ($fechaLimite<=$fechaHoy)
        {
            return true;
        }
        return false;




    }



}