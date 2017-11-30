<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 31/08/2017
 * Time: 11:54
 */

namespace backend\models;


class AgenteSearcherFactory
{
    private $flujoProceso;
    private $agenteClasses;
    private $proceso;
    private $parametroAgente;


    public function __construct(FlujoProceso $flujoProceso,Proceso $proceso,string $parametroAgente = null)
    {
        $this->flujoProceso = $flujoProceso;
        $this->agenteClasses = [
                                "EMPLEADO_SOLICITA"=>"backend\models\EmpleadoSolicitanteFlujoProcesoSearcher",
                                "GRUPO"=>"backend\models\GrupoFlujoProcesoSearcher",
                                "JEFE_INMEDIATO"=>"backend\models\JefeInmediatoFlujoProcesoSearcher",
                                "DEPARTAMENTO"=>'backend\models\DepartamentoFlujoProcesoSearcher',
                                "PUESTO"=>'backend\models\PuestoFlujoProcesoSearcher',
                                "EMPLEADO"=>'backend\models\EmpleadoFlujoProcesoSearcher',
                                "JEFE_SUPERIOR"=>'backend\models\JefeSuperiorFlujoProcesoSearcher',
                                "RELACION_EMP"=>'backend\models\RelacionEmpleadoFlujoProcesoSearcher'
                                ];
        $this->proceso = $proceso;
        $this->parametroAgente = $parametroAgente;

    }

    /**
     * @param string $type
     * @return IAgenteFlujoProcesoSearcher|null
     */

    public function createAgenteSearcher(string $type){
        if(array_key_exists($type,$this->agenteClasses)){
            return new $this->agenteClasses[$type]($this->flujoProceso,$this->proceso,$this->parametroAgente);
        }
        return null;

    }

}