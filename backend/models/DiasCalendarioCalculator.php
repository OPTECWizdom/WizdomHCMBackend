<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 09/11/2017
 * Time: 16:04
 */

namespace backend\models;


use backend\workflowManagers\MovimientoVacacionesEjecutorManager;
use Faker\Provider\DateTime;

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
        return $difference;

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