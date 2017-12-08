<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 19/10/2017
 * Time: 16:34
 */

namespace backend\models\movimientosVacaciones\vacacionesDiasCalculator;


use backend\models\movimientosVacaciones\MovimientoVacaciones;
use backend\models\empleado\Empleado;
use backend\models\movimientosVacaciones\controlAjusteVacacionesMovimiento\ControlAjusteVacacionesMovimiento;
use backend\models\empleado\vacacionesEmpleado\ControlAjusteVacacionAcumulado;
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
     * @var ControlAjusteVacacionesMovimiento $controlAjusteVacacionesMovimiento
     */
    private $controlAjusteVacacionesMovimiento;


    public function calcularVacaciones()
    {
        try
        {
            $diasCalculados = $this->calcularDiasVacacionesConAcumulado();
            $diasHabiles = $diasCalculados[0];
            $diasAjuste = $diasCalculados[1];
            $this->getAcumuladoRelacionadoAVacaciones($diasAjuste);
            $this->adjuntarEventoGuardarCalculo();
            return ['dias_habiles'=>$diasHabiles];
        }
        catch (\Exception $e)
        {
            return $this->getMovimientoVacaciones()->getAttribute('dias_habiles');
        }


    }

    public function adjuntarEventoGuardarCalculo()
    {
        $this->movimientoVacaciones->on(MovimientoVacaciones::EVENT_AFTER_INSERT,[$this,'guardarCalculo']);
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

    private function getEmpleado()
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
    private function getControlAjusteVacacionesAcumulado()
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
    private function insertNuevoAcumulado()
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


    private function calcularDiasVacacionesConAcumulado()
    {
        $diasAjustes = $this->getControlAjusteVacacionesAcumulado()->getAttribute("dias_ajuste");
        $diasHabiles = $this->movimientoVacaciones->getAttribute("dias_habiles");
        $diasHabilesConAjuste = $diasHabiles+($diasHabiles*0.2)+$diasAjustes;
        $diasAjustes = $diasHabilesConAjuste-intval($diasHabilesConAjuste);
        return [intval($diasHabilesConAjuste),$diasAjustes];

    }

    /**
     * @return MovimientoVacaciones
     */

    private function getMovimientoVacaciones()
    {
        return $this->movimientoVacaciones;
    }

    /**
     * @param $diasAjustes
     * @return ControlAjusteVacacionesMovimiento
     */


    private function guardarAcumuladoRelacionadoAVacaciones()
    {

        $this->controlAjusteVacacionesMovimiento->save();


    }

    public function guardarCalculo()
    {
        $this->guardarAcumuladoRelacionadoAVacaciones();
    }


    private function getAcumuladoRelacionadoAVacaciones($diasAjustes)
    {
        $controlAjusteVacacionesMoV = new ControlAjusteVacacionesMovimiento();
        $atributosVacaciones = $this->getMovimientoVacaciones()->getAttributes(['compania','tipo_mov','consecutivo_movimiento']);
        $atributosEmpleado = $this->getEmpleado()->getAttributes(['compania','codigo_empleado']);
        $atributosEmpleado['emp_compania']=$atributosEmpleado['compania'];
        $controlAjusteVacacionesMoV->setAttributes(array_merge($atributosEmpleado,$atributosVacaciones));
        $controlAjusteVacacionesMoV->setAttribute('dias_ajuste',$diasAjustes);
        $this->controlAjusteVacacionesMovimiento = $controlAjusteVacacionesMoV;
        return $controlAjusteVacacionesMoV;

    }


}