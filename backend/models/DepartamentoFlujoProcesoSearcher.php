<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 11/09/2017
 * Time: 11:24
 */

namespace app\models;


class DepartamentoFlujoProcesoSearcher implements IAgenteFlujoProcesoSearcher
{
    private $flujoProceso;
    private $proceso;
    private $parametroAgente;

    public function __construct(FlujoProceso $flujoProceso,Proceso $proceso,string $parametroAgente = null)
    {
        $this->flujoProceso;
        $this->proceso = $proceso;
        $this->parametroAgente;
    }


    public function search()
    {
        $arrayParmsAgente = explode("-",$this->parametroAgente);
        if(count($arrayParmsAgente)==2)
        {
            $compania = $arrayParmsAgente[0];
            $organigrama = $arrayParmsAgente[1];
            $searchConditions = ["compania"=>$compania,"ogranigrama"=>$organigrama];
            $empleados = Empleado::find()->where($searchConditions)->all();
            return $empleados;


        }
        return [] ;




    }

}