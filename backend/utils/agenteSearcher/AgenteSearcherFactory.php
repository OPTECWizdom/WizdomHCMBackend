<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 31/08/2017
 * Time: 11:54
 */

namespace backend\utils\agenteSearcher;


class AgenteSearcherFactory
{
    /**
     * @var IAgenteSearchable $searchableObject
     */
    private $searchableObject;
    private $agenteClasses = [
        "EMPLEADO_SOLICITA"=>'backend\utils\agenteSearcher\empleadoSearcher\EmpleadoSolicitanteSearcher',
        "GRUPO"=>'backend\utils\agenteSearcher\grupoSearcher\GrupoSearcher',
        "JEFE_INMEDIATO"=>'backend\utils\agenteSearcher\jefeSearcher\JefeInmediatoSearcher',
        "DEPARTAMENTO"=>'backend\utils\agenteSearcher\departamentoSearcher\DepartamentoSearcher',
        "PUESTO"=>'backend\utils\agenteSearcher\puestoSearcher\PuestoSearcher',
        "EMPLEADO"=>'backend\utils\agenteSearcher\empleadoSearcher\EmpleadoSearcher',
        "JEFE_SUPERIOR"=>'backend\utils\agenteSearcher\jefeSearcher\JefeSuperiorSearcher',
        "RELACION_EMP"=>'backend\utils\agenteSearcher\relacionEmpleadoSearcher\RelacionEmpleadoSearcher'
    ];
    private $extraParms;
    public function __construct(IAgenteSearchable $searchableObject, string $extraParms = null)
    {

        $this->searchableObject = $searchableObject;
        $this->extraParms;
    }

    /**
     * @param string $type
     * @return IAgenteSearcher|null
     */

    public function createAgenteSearcher(string $type){
        if(array_key_exists($type,$this->agenteClasses)){
            return new $this->agenteClasses[$type]($this->searchableObject,$this->extraParms);
        }
        return null;

    }

}