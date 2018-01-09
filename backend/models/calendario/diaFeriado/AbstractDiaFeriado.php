<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 09/01/2018
 * Time: 9:29
 */

namespace backend\models\calendario\diaFeriado;


use yii\db\ActiveRecord;

abstract class AbstractDiaFeriado extends ActiveRecord implements IDiaFeriado
{

    public abstract function getDiaFeriado();

    public abstract function getMesFeriado();

    public abstract function getNombreDiaFeriado();


    public function fields()
    {
        $parentFields = parent::fields();
        $fields['numero_dia_feriado']='diaFeriado';
        $fields['numero_mes_feriado']='mesFeriado';
        $fields['nombre_dia_feriado']='nombreDiaFeriado';
        return array_merge($parentFields,$fields);

    }

}