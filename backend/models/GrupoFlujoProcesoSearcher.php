<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 31/08/2017
 * Time: 15:45
 */

namespace backend\models;


class GrupoFlujoProcesoSearcher implements IAgenteFlujoProcesoSearcher
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
        $empleados = array();
        $grupo = $this->parametroAgente;
        $secUserGroup = SecurityUserGroup::find()->where(["group_id"=>$grupo])->all();
        foreach ($secUserGroup as $user)
        {
          $empleado = Empleado::find()->where(["username"=>$user->getAttribute("login")])->one();
          $empleados[] = $empleado;
        }
        return $empleados;



    }

}