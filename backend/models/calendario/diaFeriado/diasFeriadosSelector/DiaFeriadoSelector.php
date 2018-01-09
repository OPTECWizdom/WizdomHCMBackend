<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 09/01/2018
 * Time: 15:01
 */

namespace backend\models\calendario\diaFeriado\diasFeriadosSelector;
use backend\models\calendario\diaFeriado\DiaFeriado;

class DiaFeriadoSelector implements IDiaFeriadoSelector
{

    public function getDiasFeriados($empleado)
    {
        return DiaFeriado::find()->where(["compania"=>$empleado->getAttribute('compania')])->all();
    }

}