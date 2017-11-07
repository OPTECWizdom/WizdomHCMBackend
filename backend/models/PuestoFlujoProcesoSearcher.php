<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 11/09/2017
 * Time: 11:24
 */

namespace backend\models;


class PuestoFlujoProcesoSearcher implements IAgenteFlujoProcesoSearcher
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
        if(count($arrayParmsAgente)==3)
        {
            $compania = $arrayParmsAgente[0];
            $organigrama = $arrayParmsAgente[1];
            $puesto = $arrayParmsAgente[2];
            $searchConditions = ["compania"=>$compania,"codigo_nodo_organigrama"=>$organigrama,"codigo_puesto"=>$puesto];
            $empleados = Empleado::find()->where($searchConditions)->all();
            return $empleados;


        }
        return [] ;




    }

}