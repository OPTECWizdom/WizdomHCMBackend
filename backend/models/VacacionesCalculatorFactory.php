<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 19/10/2017
 * Time: 15:31
 */

namespace backend\models;


/**
 * Class VacacionesCalculatorFactory
 * @package backend\models
 */

class VacacionesCalculatorFactory
{
    /**
     * @var MovimientoVacaciones $movimientoVacaciones
     */

    private $movimientoVacaciones;

    /**
     * @var string $vacacionesCalculatorsNames;
     */
    private $vacacionesCalculatorsNames;
    /**
     * @var IDiasVacacionesCalculator[] $vacacionesCalculators
     */
    private $vacacionesCalculators;

    /**
     * VacacionesCalculatorFactory constructor.
     * @param MovimientoVacaciones $movimientoVacaciones
     */

    public function __construct(MovimientoVacaciones $movimientoVacaciones)
    {
        $this->movimientoVacaciones = $movimientoVacaciones;
        $this->vacacionesCalculatorsNames = [5=>'backend\models\CincoMasUnoVacacionesCalculator'];
        foreach ($this->vacacionesCalculatorsNames as $key=>$value)
        {
            $this->vacacionesCalculators[$key] = new $value();
        }
    }

    /**
     * @return MovimientoVacaciones
     */
    public function getMovimientoVacaciones(): MovimientoVacaciones
    {
        return $this->movimientoVacaciones;
    }

    /**
     * @param MovimientoVacaciones $movimientoVacaciones
     */
    public function setMovimientoVacaciones(MovimientoVacaciones $movimientoVacaciones)
    {
        $this->movimientoVacaciones = $movimientoVacaciones;
    }




    /**
     * @param
     * @return IDiasVacacionesCalculator|null
     */

    public function getVacacionesCalculator()
    {
        try {
            $diasTrabajo = $this->getDiasDeTrabajo();
            if (array_key_exists($diasTrabajo, $this->vacacionesCalculators)) {
                $this->vacacionesCalculators[$diasTrabajo]->setMovimientoVacaciones($this->movimientoVacaciones);
                return $this->vacacionesCalculators[$diasTrabajo];
            }
            return null;
        }
        catch(\Exception $e)
        {
            return null;
        }

    }

    /**
     * @return int
     */

    public function getDiasDeTrabajo() : int
    {
        $horarioActual = $this->getHorarioEmpleado();
        if(!empty($horarioActual))
        {
            $horarioPk = Horario::primaryKey();
            $horario = new Horario();
            $horario->setAttributes($horarioActual->getAttributes($horarioPk));
            $diasTrabajo = $horario->getDetalleHorario()->where(['trabaja'=>'T'])->count();
            return $diasTrabajo;


        }
        return 0;

    }
    public function getHorarioEmpleado()
    {
        $pksEmpleado = Empleado::primaryKey();
        $empleado = new Empleado();
        $empleado->setAttributes($this->movimientoVacaciones->getAttributes($pksEmpleado));
        $horarioActual = $empleado->getHorarioActual()->one();
        return $horarioActual;

    }



}