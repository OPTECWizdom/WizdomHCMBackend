<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 09/01/2018
 * Time: 14:48
 */

namespace backend\models\calendario\diaFeriado\diasFeriadosSelector;

use backend\models\calendario\diaFeriado\DiaFeriadoCatalogo;

class DiaFeriadoCatalogoSelector implements IDiaFeriadoSelector
{

    public function getDiasFeriados($empleado)
    {
        $organigrama = $empleado->getOrganigrama()->one();
        if(!empty($organigrama))
        {
            $catalogoDiasFeriados = $organigrama->getAttribute('catalogo_dias_feriados');
            if(!empty($catalogoDiasFeriados))
            {
                $compania = $organigrama->getAttribute('compania');
                return DiaFeriadoCatalogo::find()->where(['compania'=>$compania,'catalogo_dias_feriados'=>$catalogoDiasFeriados])->all();
            }
        }
        return [];
    }

}