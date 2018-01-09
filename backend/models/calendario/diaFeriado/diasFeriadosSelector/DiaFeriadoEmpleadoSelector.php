<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 09/01/2018
 * Time: 14:40
 */

namespace backend\models\calendario\diaFeriado\diasFeriadosSelector;


use backend\models\calendario\diaFeriado\DiaFeriadoEmpleado;

class DiaFeriadoEmpleadoSelector implements IDiaFeriadoSelector
{


    public function getDiasFeriados($empleado)
    {
        try {
            $diasFeriadoEmpleadoPks = $empleado->getAttributes(["compania", "codigo_empleado"]);
            $diasFeriadoEmpleadoPks["nomina_pemanente"] = $empleado->getAttribute('nomina_primaria');
            $diasFeriadoEmpleadoPks["ano_natural"] = date('Y');
            return DiaFeriadoEmpleado::find()->where($diasFeriadoEmpleadoPks)->all();

        }
        catch(\Exception $e)
        {
            return [];
        }

    }






}