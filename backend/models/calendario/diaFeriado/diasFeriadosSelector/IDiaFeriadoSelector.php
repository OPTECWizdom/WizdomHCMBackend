<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 09/01/2018
 * Time: 14:29
 */

namespace backend\models\calendario\diaFeriado\diasFeriadosSelector;

use backend\models\empleado\Empleado;
use backend\models\calendario\diaFeriado\IDiaFeriado;

interface IDiaFeriadoSelector
{
    /**
     *
     * @param Empleado $empleado
     * @return IDiaFeriado[]
     */
    public function getDiasFeriados($empleado);


}