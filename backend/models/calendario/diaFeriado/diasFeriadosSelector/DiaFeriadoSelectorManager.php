<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 09/01/2018
 * Time: 15:03
 */

namespace backend\models\calendario\diaFeriado\diasFeriadosSelector;


class DiaFeriadoSelectorManager extends AbstractDiaFeriadoSelectorManager
{

    public function setFeriadosClasses()
    {
         $this->feriadosClass = [
            "DiaFeriadoEmpleadoSelector",
            "DiaFeriadoCatalogoSelector",
            "DiaFeriadoSelector"
         ];
    }

}