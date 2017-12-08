<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 08/12/2017
 * Time: 17:20
 */

namespace backend\models\procesoModelConnector\procesoObserver;

use backend\models\proceso\Proceso;
class ProcesoObserver implements  IProcesoObserver
{
    public function insertProceso($procesoSubject)
    {
        $compania = $procesoSubject->getAttribute('compania');
        $tipo_flujo_proceso = $procesoSubject::tableName();
        $sistema_procedencia="RHU";
        $codigo_empleado = $procesoSubject->getAttribute('codigo_empleado');
        $proceso = new Proceso();
        $proceso->setAttributes(["compania"=>$compania,"tipo_flujo_proceso"=>$tipo_flujo_proceso,
                                "sistema_procedencia"=>$sistema_procedencia,"codigo_empleado"=>$codigo_empleado]);
        if($proceso->save())
        {
            $procesoSubject->setProceso($proceso);
            return true;
        }
        return false;
    }


}