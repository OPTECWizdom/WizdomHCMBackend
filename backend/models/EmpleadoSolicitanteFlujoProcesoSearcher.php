<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 31/08/2017
 * Time: 12:07
 */

namespace app\models;


class EmpleadoSolicitanteFlujoProcesoSearcher implements IAgenteFlujoProcesoSearcher
{

    private $flujoProceso;
    private $proceso;

    public function __construct(FlujoProceso $flujoProceso,Proceso $proceso)
    {
        $this->flujoProceso;
        $this->proceso = $proceso;
    }


    public function search()
    {
        $empleadoPks = $this->proceso->getAttributes(Empleado::primaryKey());

        $empleado = Empleado::find()->where($empleadoPks)->one();
        return [$empleado];

    }



}