<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 11/09/2017
 * Time: 11:24
 */

namespace app\models;


class EmpleadoFlujoProcesoSearcher implements IAgenteFlujoProcesoSearcher
{
    private $flujoProceso;
    private $proceso;
    private $parametroAgente;

    public function __construct(FlujoProceso $flujoProceso,Proceso $proceso,string $parametroAgente = null)
    {
        $this->flujoProceso = $flujoProceso;
        $this->proceso = $proceso;
        $this->parametroAgente = $parametroAgente;

    }


    public function search()
    {
        $arrayParmsAgente = explode("-",$this->parametroAgente);
        if(count($arrayParmsAgente)==2)
        {
            $compania = $arrayParmsAgente[0];
            $codigo_empleado = $arrayParmsAgente[1];
            $searchConditions = ["compania"=>$compania,"codigo_empleado"=>$codigo_empleado];
            $empleado = Empleado::find()->where($searchConditions)->one();
            return [$empleado];


        }
        return [] ;




    }

}