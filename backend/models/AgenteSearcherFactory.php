<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 31/08/2017
 * Time: 11:54
 */

namespace app\models;


class AgenteSearcherFactory
{
    private $flujoProceso;
    private $agenteClasses;
    private $proceso;
    private $parametroAgente;


    public function __construct(FlujoProceso $flujoProceso,Proceso $proceso,string $parametroAgente = null)
    {
        $this->flujoProceso = $flujoProceso;
        $this->agenteClasses = ["EMPLEADO_SOLICITA"=>"app\models\EmpleadoSolicitanteFlujoProcesoSearcher",
                                "GRUPO"=>"app\models\GrupoFlujoProcesoSearcher",
                                "JEFE_INMEDIATO"=>"app\models\JefeInmediatoFlujoProcesoSearcher"];
        $this->proceso = $proceso;
        $this->parametroAgente = $parametroAgente;

    }

    public function createAgenteSearcher(string $type){
        if(array_key_exists($type,$this->agenteClasses)){
            return new $this->agenteClasses[$type]($this->flujoProceso,$this->proceso,$this->parametroAgente);
        }
        return null;

    }

}