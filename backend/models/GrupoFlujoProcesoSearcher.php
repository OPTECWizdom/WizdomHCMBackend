<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 31/08/2017
 * Time: 15:45
 */

namespace app\models;


class GrupoFlujoProcesoSearcher implements IAgenteFlujoProcesoSearcher
{


    private $flujoProceso;
    private $proceso;
    private $flujoTipoProcesoNotificacion;

    public function __construct(FlujoProceso $flujoProceso,Proceso $proceso,FlujoTipoProcesoNotificacion $flujoTipoProcesoNotificacion)
    {
        $this->flujoProceso;
        $this->proceso = $proceso;
        $this->flujoTipoProcesoNotificacion = $flujoTipoProcesoNotificacion;
    }



    public function search()
    {
        $empleados = array();
        $grupo = $this->flujoTipoProcesoNotificacion->getAttribute("parametro_agente");
        $secUserGroup = SecurityUserGroup::find()->where(["group_id"=>grupo])->all();
        foreach ($secUserGroup as $user)
        {
          $empleado = Empleado::find()->where(["username"=>$user->getAttribute("login")])->one();
          $empleados[] = $empleado;
        }
        return $empleados;



    }

}