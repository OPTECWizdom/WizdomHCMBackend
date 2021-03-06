<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 09/11/2017
 * Time: 14:48
 */

namespace backend\models\calendario\diaFeriado;


use yii\db\ActiveRecord;

class DiaFeriadoCatalogo extends AbstractDiaFeriado
{



    public static function tableName()
    {
        return "DIAS_FERIADOS_X_CATALOGO";
    }

    public static function primaryKey()
    {
        return ["compania","catalogo_dias_feriados","dia","mes"];
    }



    public function getDiaFeriado()
    {
        return $this->dia;
    }

    public function getMesFeriado()
    {
        return $this->mes;
    }
    public function getNombreDiaFeriado()
    {
        return $this->nombre_dia_feriado;
    }


}