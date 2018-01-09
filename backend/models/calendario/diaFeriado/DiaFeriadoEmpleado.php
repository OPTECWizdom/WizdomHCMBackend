<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 09/01/2018
 * Time: 9:27
 */

namespace backend\models\calendario\diaFeriado;



class DiaFeriadoEmpleado extends AbstractDiaFeriado
{

    public static function tableName()
    {
        return "DIAS_FERIADOS_X_EMPLEADO";
    }

    public static function primaryKey()
    {
        return ["compania","nomina_permanente","codigo_empleado","dia","mes","ano_natural"];

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