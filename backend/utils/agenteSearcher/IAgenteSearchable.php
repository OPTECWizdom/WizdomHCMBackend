<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 08/12/2017
 * Time: 11:49
 */

namespace backend\utils\agenteSearcher;

use backend\models\empleado\Empleado;
interface IAgenteSearchable
{
    /**
     * @return Empleado
     */
    public function getEmpleadoPrincipal();

}