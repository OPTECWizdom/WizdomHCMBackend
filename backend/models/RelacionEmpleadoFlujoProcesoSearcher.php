<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 31/08/2017
 * Time: 15:45
 */

namespace backend\models;


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

    public function search($config = [])
    {
        $tipoRelacion = $this->parametroAgente;
        $codigoEmpleadoSolicitante = $this->proceso->getAttribute("codigo_empleado");
        $compania = $this->proceso->getAttribute("compania");
        $extraFields = array_key_exists('relations',$config)?$config['relations']:[];
        $empleados = Empleado::find()
                        ->with($extraFields)
                        ->joinWith('relacionesEmpleadosInvolucradas',false,'RIGHT JOIN')
                        ->where ([  '{{relaciones_empleado}}.compania'=>$compania,
                                    '{{relaciones_empleado}}.tipo_relacion'=>$tipoRelacion,
                                    '{{relaciones_empleado}}.codigo_empleado'=>$codigoEmpleadoSolicitante,
                                    '{{empleado}}.compania'=>$compania])->all();
        return $empleados;
    }


}