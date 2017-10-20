<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 19/10/2017
 * Time: 15:29
 */

namespace app\models;


interface IDiasVacacionesCalculator
{
    /**
     * @return int
     */
    public function calcularVacaciones() : double;


    /**
     * @param MovimientoVacaciones $movimientoVacaciones
     * @return mixed
     */

    public function setMovimientoVacaciones(MovimientoVacaciones $movimientoVacaciones);

}