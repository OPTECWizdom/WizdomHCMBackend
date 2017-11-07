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
     * @return int
     */
    public function calcularVacaciones() : int;




    /**
     * @param MovimientoVacaciones $movimientoVacaciones
     * @return mixed
     */

    public function setMovimientoVacaciones(MovimientoVacaciones $movimientoVacaciones);

}