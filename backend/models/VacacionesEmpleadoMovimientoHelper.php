<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 19/10/2017
 * Time: 10:37
 */

namespace app\models;

/**
 * Class VacacionesEmpleadoMovimientoHelper
 * @package app\models
 *
 * @property MovimientoVacaciones $movimientoVacaciones
 * @property  VacacionesEmpleadoMovimiento[] $vacacionesEmpleadoMovimiento
 */

class VacacionesEmpleadoMovimientoHelper
{


    /**
     * @var MovimientoVacaciones
     */
    private $movimientoVacaciones;
    /**
     * @var VacacionesEmpleadoMovimiento[]
     */

    private $vacacionesEmpleadoMovimiento;


    public function __construct($movimientoVacaciones)
    {
        $this->movimientoVacaciones = $movimientoVacaciones;
        $this->vacacionesEmpleadoMovimiento = $this->getVacacionesEmpleadoMovimiento();
    }


    /**
     * @return MovimientoVacaciones
     */

    public function getMovimientoVacaciones()
    {
        return $this->movimientoVacaciones;

    }

    /**

     *
     * @param MovimientoVacaciones $movimientoVacaciones
     *
     */
    public function setMovimientoVacaciones(MovimientoVacaciones $movimientoVacaciones)
    {
        $this->movimientoVacaciones = $movimientoVacaciones;
    }

    /**
     *
     * @return VacacionesEmpleadoMovimiento[]
     */

    public function getVacacionesEmpleadoMovimiento():array
    {
        if(!empty($this->vacacionesEmpleadoMovimiento))
        {
            return $this->vacacionesEmpleadoMovimiento;
        }
        $vacacionesEmpleado = $this->getVacacionesEmpleadoDisponibles();
        $vacacionesEmpleadoMovimientos = $this->setVacacionesEmpleadoMovimientosData($vacacionesEmpleado);
        return $vacacionesEmpleadoMovimientos;


    }

    /**
     *
     *@return  VacacionesEmpleado[]
     */
    private function getVacacionesEmpleadoDisponibles():array{
        $movimientoVacaciones = $this->movimientoVacaciones;
        $compania = $movimientoVacaciones->getAttribute(["compania"]);
        $codigoEmpleado = $movimientoVacaciones->getAttribute(["codigo_empleado"]);
        $vacacionesEmpleado = VacacionesEmpleado::find()->where(["compania"=>$compania, "codigo_empleado"=>$codigoEmpleado])->andWhere(['>','dias_disponibles',0])->all();
        return $vacacionesEmpleado;

    }





    /**
     *
     * @param VacacionesEmpleado[] $vacacionesEmpleado
     * @return VacacionesEmpleadoMovimiento[]
     */

    private function setVacacionesEmpleadoMovimientosData(array $vacacionesEmpleado) :array
    {
        $vacacionesEmpleadoMovimiento = [];
        foreach ($vacacionesEmpleado as $vacacionEmpleado)
        {
            $vacacionesEmpleadoMovimiento[] = $this->setVacacionEmpleadoMovimientoData($vacacionEmpleado);

        }
        return $vacacionesEmpleadoMovimiento;

    }

    /**
     * @param VacacionesEmpleado $vacacionesEmpleado
     * @return VacacionesEmpleadoMovimiento
     */

    private function setVacacionEmpleadoMovimientoData(VacacionesEmpleado $vacacionesEmpleado):VacacionesEmpleadoMovimiento
    {
        $vacacionesEmpleadoMovimiento = new VacacionesEmpleadoMovimiento();
        $vacacionesEmpleadoMovimiento->setAttributes($vacacionesEmpleado->getAttributes(["regimen_vacaciones","periodo","consecutivo",
	                                                                                                "dias_disponibles","dias_disfrutados"]));
        $vacacionesEmpleadoMovimiento->setAttributes($this->movimientoVacaciones->getAttributes(["compania","tipo_mov","codigo_empleado",
                                                                                                "consecutivo_movimiento"]));
        return $vacacionesEmpleadoMovimiento;

    }




}