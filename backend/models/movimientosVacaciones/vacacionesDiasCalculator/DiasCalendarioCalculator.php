<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 09/11/2017
 * Time: 16:04
 */

namespace backend\models\movimientosVacaciones\vacacionesDiasCalculator;


use backend\models\movimientosVacaciones\MovimientoVacaciones;

class DiasCalendarioCalculator implements IDiasVacacionesCalculator
{

    /**
     * @var MovimientoVacaciones $movimientoVacacion
     */
    private $movimientoVacacion;


    public function calcularVacaciones()
    {
        $fechaInicial = date_create($this->movimientoVacacion->getAttribute('fecha_inicial'));
        $fechaFinal = date_create($this->movimientoVacacion->getAttribute('fecha_final'));
        $difference = doubleval(date_diff($fechaInicial,$fechaFinal)->format("%a"))+1;
        return ['dias_calendario'=>$difference];

    }




    /**
     * @param MovimientoVacaciones $movimientoVacaciones
     * @return mixed
     */

    public function setMovimientoVacaciones(MovimientoVacaciones $movimientoVacaciones)
    {
        $this->movimientoVacacion = $movimientoVacaciones;
    }

}