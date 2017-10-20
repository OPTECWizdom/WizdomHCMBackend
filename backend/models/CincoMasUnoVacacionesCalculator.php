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
     * @var Empleado $empleado
     */
    private $empleado;

    /**
     * @var ControlAjusteVacacionAcumulado $controlAjusteVacacionesAcumulado
     */
    private $controlAjusteVacacionesAcumulado;
    /**
     * @return int
     */
    public function calcularVacaciones():double
    {
        return $this->getControlAjusteVacacionesAcumulado()->getAttribute("dias_ajuste");
    }

    /**
     * @param MovimientoVacaciones $movimientoVacaciones
     * @return mixed
     */
    public function setMovimientoVacaciones(MovimientoVacaciones $movimientoVacaciones)
    {
        $this->movimientoVacaciones = $movimientoVacaciones;
    }

    /**
     * @return Empleado
     */

    public function getEmpleado()
    {
        if(empty($this->empleado))
        {
            $this->empleado = new Empleado();
            $compania = $this->movimientoVacaciones->getAttribute("compania");
            $codigoEmpleado = $this->movimientoVacaciones->getAttribute("codigo_empleado");
            $this->empleado->setAttributes(["compania"=>$compania,"codigo_empleado"=>$codigoEmpleado]);

        }
        return $this->empleado;
    }




    /**
     * @return array|null|ControlAjusteVacacionAcumulado
     */
    public function getControlAjusteVacacionesAcumulado()
    {
        if(!empty($this->controlAjusteVacacionesAcumulado))
        {
            return $this->controlAjusteVacacionesAcumulado;
        }
        $empleado = $this->getEmpleado();
        $controlAjusteVacacionAcumulado =  $empleado->getControlAjusteVacacionAcumulado()->one();
        if(empty($controlAjusteVacacionAcumulado))
        {
            $controlAjusteVacacionAcumulado = $this->insertNuevoAcumulado();
        }
        $this->controlAjusteVacacionesAcumulado = $controlAjusteVacacionAcumulado;
        return $this->controlAjusteVacacionesAcumulado;

    }

    /**
     * @return ControlAjusteVacacionAcumulado
     */
    public function insertNuevoAcumulado()
    {
        $controlAjusteVacacionAcumulado =  new ControlAjusteVacacionAcumulado();
        $compania = $this->movimientoVacaciones->getAttribute("compania");
        $codigoEmpleado = $this->movimientoVacaciones->getAttribute("codigo_empleado");
        $controlAjusteVacacionAcumulado->setAttributes(["compania"=>$compania,"codigo_empleado"=>$codigoEmpleado,
                                                        "dias_ajuste"=>0]);
        $controlAjusteVacacionAcumulado->save();
        return $controlAjusteVacacionAcumulado;

    }

    /**
     * Funcion que retorna en un array los valores de las vacaciones.
     * @return int[]
     * En la posicion 0 estan los verdaderos dias habiles
     * En la posicion 1 estan los dias de ajustes que provocan ese movimiento
     */


    public function calcularDiasVacacionesConAcumulado()
    {
        $diasAjustes = $this->getControlAjusteVacacionesAcumulado()->getAttribute("dias_ajuste");
        $diasHabiles = $this->movimientoVacaciones->getAttribute("dias_habiles");
        $diasHabilesConAjuste = $diasHabiles+($diasHabiles/5)+$diasAjustes;
        $diasAjustes = $diasHabilesConAjuste-intval($diasHabilesConAjuste);
        return [intval($diasHabilesConAjuste),$diasAjustes];

    }


}