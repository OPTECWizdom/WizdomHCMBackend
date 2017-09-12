<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 11/09/2017
 * Time: 11:24
 */

namespace app\models;


class JefeSuperiorFlujoProcesoSearcher implements IAgenteFlujoProcesoSearcher
{
    private $flujoProceso;
    private $proceso;

    public function __construct(FlujoProceso $flujoProceso,Proceso $proceso,string $parametroAgente = null)
    {
        $this->flujoProceso = $flujoProceso;
        $this->proceso = $proceso;
    }


    public function search()
    {
        $jefeInmediatoSearcher = new JefeInmediatoFlujoProcesoSearcher($this->flujoProceso,$this->proceso);
        $jefeInmediato = $jefeInmediatoSearcher->search();
        if(!empty($jefeInmediato))
        {
            $empleado = $jefeInmediato[0];
            $compania = $empleado->getAttribute("compania");
            $codigoEmpleadoJefe = $empleado->getAttribute("codigo_jefe");
            $empleadoJefeSuperiorPks = ["compania"=>$compania,
                "codigo_empleado"=>$codigoEmpleadoJefe

            ];
            $empleadoJefeSuperior = Empleado::find()->where($empleadoJefeSuperiorPks)->one();
            return [$empleadoJefeSuperior];

        }
        return [];

    }

}