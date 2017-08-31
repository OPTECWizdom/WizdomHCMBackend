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
    private $flujoTipoProcesoNotificacion;


    public function __construct(FlujoProceso $flujoProceso,Proceso $proceso,FlujoTipoProcesoNotificacion $flujoTipoProcesoNotificacion)
    {
        $this->flujoProceso = $flujoProceso;
        $this->agenteClasses = ["EMPLEADO_SOLICITA"=>"app\models\EmpleadoSolicitanteFlujoProcesoSearcher",
                                "GRUPO"=>"app\models\GrupoFlujoProcesoSearcher"];
        $this->proceso = $proceso;
        $this->flujoTipoProcesoNotificacion = $flujoTipoProcesoNotificacion;

    }

    public function createAgenteSearcher(string $type){
        if(array_key_exists($type,$this->agenteClasses)){
            return new $this->agenteClasses[$type]($this->flujoProceso,$this->proceso,$this->flujoTipoProcesoNotificacion);
        }
        return null;

    }

}