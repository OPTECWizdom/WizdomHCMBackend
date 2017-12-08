<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 09/11/2017
 * Time: 15:58
 */

namespace backend\models\movimientosVacaciones\vacacionesDiasCalculator;


use backend\models\movimientosVacaciones\MovimientoVacaciones;

class GeneralVacacionesCalculator implements IDiasVacacionesCalculator
{
    /**
     * @var MovimientoVacaciones $movimientoVacaciones
     */

    private $movimientoVacaciones;

    /**
     * @var IDiasVacacionesCalculator[] $calculators;
     */

    private $calculators;
    /**
     */
    public function __construct()
    {
        $calculators = [
            "dias_habiles"=>new DiasHabilesCalculator(),
            "dias_feriados"=>new DiasFeriadosCalculator(),
            "dias_calendario"=>new DiasCalendarioCalculator()
        ];

        $this->calculators = $calculators;


    }


    public function calcularVacaciones()
    {
        $results = [
            "dias_obsequiados"=>0,
            "dias_pagados"=>0,
        ];
        foreach ($this->calculators as $key=>&$value)
        {
            $value->setMovimientoVacaciones($this->movimientoVacaciones);
            $results = array_merge($results,$value->calcularVacaciones());

        }
        $results['dias_descanso'] = $results['dias_calendario']-$results['dias_habiles']-$results['dias_feriados'];
        return $results;

    }




    /**
     * @param MovimientoVacaciones $movimientoVacaciones
     * @return mixed
     */

    public function setMovimientoVacaciones(MovimientoVacaciones $movimientoVacaciones)
    {
        $this->movimientoVacaciones = $movimientoVacaciones;
    }

}