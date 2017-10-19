<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 19/10/2017
 * Time: 16:34
 */

namespace app\models;


class CincoMasUnoVacacionesCalculator implements IDiasVacacionesCalculator
{
    /**
     * @var MovimientoVacaciones $movimientoVacaciones
     */
    private $movimientoVacaciones;
    /**
     * @return int
     */
    public function calcularVacaciones(): int
    {
        return 1;
    }

    /**
     * @param MovimientoVacaciones $movimientoVacaciones
     * @return mixed
     */
    public function setMovimientoVacaciones(MovimientoVacaciones $movimientoVacaciones)
    {
        $this->movimientosVacaciones = $movimientoVacaciones;
    }

}