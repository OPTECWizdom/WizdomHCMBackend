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


    public function __construct(FlujoProceso $flujoProceso,Proceso $proceso)
    {
        $this->flujoProceso = $flujoProceso;
        $this->agenteClasses = ["EMPLEADO_SOLICITA"=>"app\models\EmpleadoSolicitanteFlujoProcesoSearcher"];
        $this->proceso = $proceso;

    }

    public function createAgenteSearcher(string $type){
        if(array_key_exists($type,$this->agenteClasses)){
            return new $this->agenteClasses[$type]($this->flujoProceso,$this->proceso);
        }
        return null;

    }

}