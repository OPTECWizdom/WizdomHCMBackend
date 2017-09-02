<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 01/09/2017
 * Time: 23:26
 */

namespace app\models;


class FlujoTipoProcesoCorreoExternoHelper
{

    private $flujoProceso;
    private $proceso;



    public function __construct(FlujoProceso $flujoProceso)
    {
        $this->flujoProceso  = $flujoProceso;
        $ids = Proceso::primaryKey();
        $primaryKeysProceso = $this->flujoProceso->getAttributes($ids);
        $this->proceso = Proceso::find()->where($primaryKeysProceso)->one();
    }


    public function sendEmail()
    {

    }




    public function getAgentesWithoutEmailSent()
    {
        $ids = FlujoProceso::primaryKey();
        $primaryKeys = $this->flujoProceso->getAttributes($ids);
        $flujoProcesoAgentes = FlujoProcesoAgente::find()->where($primaryKeys)->where(["correo_enviado"=>'N'])->all();
        return $flujoProcesoAgentes;

    }

    private function getCorreosOfFlujoTipoProceso()
    {
        $ids = FlujoTipoProceso::primaryKey();
        $primaryKeys = $this->flujoProceso->getAttributes($ids);
        $correos = FlujoTipoProcesoCorreoExterno::find()->where($primaryKeys)->all();
        return $correos;

    }

    public function getEmpleadosFromAgente(FlujoProcesoAgente $flujoProcesoAgente)
    {
        $parametroAgente = $flujoProcesoAgente->getAttribute("parametro_agente");
        $agente = $flujoProcesoAgente->getAttribute("agente");
        $agenteSearcherFactory = new AgenteSearcherFactory($this->flujoProceso,$this->proceso,$parametroAgente);
        $agenteSearcher = $agenteSearcherFactory->createAgenteSearcher($agente);
        return $agenteSearcher->search();


    }






}