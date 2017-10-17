<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 31/08/2017
 * Time: 15:45
 */

namespace app\models;


class RelacionEmpleadoFlujoProcesoSearcher implements IAgenteFlujoProcesoSearcher
{


    private $flujoProceso;
    private $proceso;
    private $parametroAgente;

    public function __construct(FlujoProceso $flujoProceso,Proceso $proceso,string $parametroAgente)
    {
        $this->flujoProceso = $flujoProceso;
        $this->proceso = $proceso;
        $this->parametroAgente = $parametroAgente;
    }

    public function search()
    {
        $empleados = [];
        $tipoRelacion = $this->parametroAgente;
        $codigoEmpleadoSolicitante = $this->proceso->getAttribute("codigo_empleado");
        $compania = $this->proceso->getAttribute("compania");
        $relacionesEmpleados = RelacionEmpleado::find()->where(["compania"=>$compania,"codigo_empleado"=>$codigoEmpleadoSolicitante,
                                                            "tipo_relacion"=>$tipoRelacion])->all();
        foreach ($relacionesEmpleados as $relacionEmpleado){
            $codigoEmpleadoRelacion = $relacionEmpleado->getAttribute("codigo_empleado_relacion");
            $empleado = Empleado::find()->where(["compania"=>$compania,
                                                "codigo_empleado"=>$codigoEmpleadoRelacion])->one();
            if(!empty($empleado))
            {
                $empleados[] = $empleado;
            }

        }
        return $empleados;
    }


}