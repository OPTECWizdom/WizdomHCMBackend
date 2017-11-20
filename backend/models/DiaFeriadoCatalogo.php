<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 09/11/2017
 * Time: 14:48
 */

namespace backend\models;


use yii\db\ActiveRecord;

class DiaFeriadoCatalogo extends ActiveRecord
{


    public static function tableName()
    {
        return "DIAS_FERIADOS_X_CATALOGO";
    }

    public static function primaryKey()
    {
        return ["compania","catalogo_dias_feriados","dia","mes"];
    }


    public function fields()
    {
        $fields = parent::fields();
        $fields['numero_dia_feriado']='dia';
        $fields['numero_mes_feriado']='mes';
        return $fields;
    }

}