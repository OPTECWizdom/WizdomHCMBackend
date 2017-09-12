<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 01/09/2017
 * Time: 23:40
 */

namespace app\models;


class JefeInmediatoFlujoProcesoSearcher implements IAgenteFlujoProcesoSearcher
{
    private $flujoProceso;
    private $proceso;

    public function __construct(FlujoProceso $flujoProceso,Proceso $proceso,string $parametroAgente  = null)
    {
        $this->flujoProceso = $flujoProceso;
        $this->proceso = $proceso;
    }


    public function search()
    {
        $empleadoPks = $this->proceso->getAttributes(Empleado::primaryKey());

        $empleado = Empleado::find()->where($empleadoPks)->one();
        if(!empty($empleado)){
            $compania = $empleado->getAttribute("compania");
            $codigoEmpleadoJefe = $empleado->getAttribute("codigo_jefe");
            $empleadoJefePks = ["compania"=>$compania,
                                "codigo_empleado"=>$codigoEmpleadoJefe

            ];

            $empleadoJefe = Empleado::find()->where($empleadoJefePks)->one();
            return [$empleadoJefe];
        }
        return [];

    }

}