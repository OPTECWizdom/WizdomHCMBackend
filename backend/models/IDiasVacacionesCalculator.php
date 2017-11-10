<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 19/10/2017
 * Time: 15:29
 */

namespace backend\models;


interface IDiasVacacionesCalculator
{
    /**
     */
    public function calcularVacaciones();




    /**
     * @param MovimientoVacaciones $movimientoVacaciones
     * @return mixed
     */

    public function setMovimientoVacaciones(MovimientoVacaciones $movimientoVacaciones);

}