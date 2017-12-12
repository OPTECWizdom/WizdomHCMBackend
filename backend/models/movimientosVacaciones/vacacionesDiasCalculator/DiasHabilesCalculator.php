<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 09/11/2017
 * Time: 16:13
 */

namespace backend\models\movimientosVacaciones\vacacionesDiasCalculator;
use backend\models\movimientosVacaciones\MovimientoVacaciones;
use backend\models\empleado\Empleado;
use backend\models\horario\Horario;
use backend\models\horario\detalleHorario\DetalleHorario;
use backend\models\calendario\diaFeriado\IDiaFeriado;

class DiasHabilesCalculator implements IDiasVacacionesCalculator
{
    /**
     * @var MovimientoVacaciones $movimientoVacacion
     */
    private $movimientoVacacion;
    /**
     * @var array
     */
    private $fechas = [];

    /**
     * @var Empleado $empleado
     */

    private $empleado;


    public function calcularVacaciones()
    {
        $diasTrabajo = $this->getDiasTrabajo();
        if(!empty($diasTrabajo))
        {
            return ['dias_habiles'=>$this->contarDiasHabiles($diasTrabajo)];
        }
        return ['dias_habiles'=>0];

    }

    /**
     * @return DetalleHorario[]
     */


    private function getDiasTrabajo()
    {
        $empleado = new Empleado();
        $empleado->setAttributes($this->movimientoVacacion->getAttributes(Empleado::primaryKey()));
        /**
         * @var Horario $horarioActual
         */
        $horarioActual = $empleado->getHorarioActual()->one();
        $diasTrabajo = [];
        if(!empty($horarioActual))
        {
            $horario = new Horario();
            $horario->setAttributes($horarioActual->getAttributes(Horario::primaryKey()));
            $diasTrabajo = $horario->getDiasTrabajo()->all();
        }
        return $diasTrabajo;

    }

    /**
     * @param DetalleHorario[] $diasTrabajo
     * @return int
     */

    private function contarDiasHabiles($diasTrabajo)
    {
        /**
         * @var \DateTimeInterface $dia
         */
        $diasTrabajo = $this->convertDays($diasTrabajo);
        $fechaInicial = new \DateTime($this->movimientoVacacion->getAttribute('fecha_inicial'));
        $fechaFinal = new \DateTime($this->movimientoVacacion->getAttribute('fecha_final'));
        $fechaFinal->add(new \DateInterval('P1D'));
        $diasHabiles = 0;
        $intervalo =  new \DateInterval('P1D');
        $periodo = new \DatePeriod($fechaInicial,$intervalo,$fechaFinal);
        foreach ($periodo as $dia)
        {
            $diaFormat = $dia->format('D');
            if(in_array($diaFormat,$diasTrabajo))
            {
                $this->addFecha($dia->format('n'),$dia->format('j'));
                $diasHabiles++;
            }
        }
        $countFeriados = $this->getCountFeriados();
        return $diasHabiles - $countFeriados;
    }

    /**
     * @param $mes
     * @param $dia
     */
    private function addFecha($mes,$dia)
    {
        $this->fechas[]="$mes-$dia";

    }


    private function getCountFeriados()
    {
        $empleado = $this->getEmpleado();
        if(!empty($empleado))
        {
            $diasFeriados = $empleado->getDiasFeriados();
            $countFeriados = array_map([$this,'getFeriadoInDiaHabil'],$diasFeriados);
            return (!empty($countFeriados) && in_array('feriado',$countFeriados))?array_count_values($countFeriados)['feriado']:0;
        }
        return 0;
    }

    /**
     * @param IDiaFeriado $diaFeriado
     *
     * @return int;
     */

    public function getFeriadoInDiaHabil($diaFeriado)
    {
        $fechasVacaciones = $this->fechas;
        $fechaDia = $diaFeriado->getMesFeriado()."-".$diaFeriado->getDiaFeriado();
        if(in_array($fechaDia,$fechasVacaciones))
        {
            return 'feriado';
        }
        return 'no-feriado';

    }

    /**
     * @return Empleado
     */

    public function getEmpleado(){
        if(!empty($this->empleado))
        {
            return $this->empleado;
        }
        $this->empleado = $this->movimientoVacacion->getEmpleado()->one();
        return $this->empleado;
    }


    /**
     * @param MovimientoVacaciones $movimientoVacaciones
     * @return mixed
     */

    public function setMovimientoVacaciones(MovimientoVacaciones $movimientoVacaciones)
    {
        $this->movimientoVacacion = $movimientoVacaciones;
    }

    /**
     * @param DetalleHorario[] $dias
     * @return mixed
     */


    private function convertDays($dias)
    {
        $transform = [
            'L'=>'Mon',
            'K'=>'Tue',
            'M'=>'Wed',
            'J'=>'Thu',
            'V'=>'Fri',
            'S'=>'Sat',
            'D'=>'Sun'
        ];
        foreach ($dias as &$dia)
        {
            $dia = $transform[$dia->getAttribute('dia_semana')];
        }
        return $dias;
    }


}