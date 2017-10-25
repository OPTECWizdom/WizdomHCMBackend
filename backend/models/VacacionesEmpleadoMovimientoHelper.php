<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 19/10/2017
 * Time: 10:37
 */

namespace app\models;




class VacacionesEmpleadoMovimientoHelper
{


    /**
     * @var MovimientoVacaciones
     */
    private $movimientoVacaciones;
    /**
     * @var VacacionesEmpleado[]
     */

    private $vacacionesEmpleado;

    /**
     * @var Empleado
     */
    private $empleado;

    /**
     * VacacionesEmpleadoMovimientoHelper constructor.
     * @param MovimientoVacaciones $movimientoVacaciones
     */

    public function __construct(MovimientoVacaciones $movimientoVacaciones)
    {
        $this->movimientoVacaciones = $movimientoVacaciones;
        $this->setEmpleadoFromMovimientosVacaciones();
        $this->setVacacionesEmpleadoFromEmpleado();
    }

    private function setVacacionesEmpleadoFromEmpleado()
    {
        $maximoPeriodo = $this->getUltimoPeriodoVacacionesEmpleado();
        $this->vacacionesEmpleado = $this->empleado->getVacacionesEmpleado()
                                    ->where(['>','dias_disponibles',0])
                                    ->orWhere(["periodo"=>$maximoPeriodo])
                                    ->orderBy("periodo asc")->all();
    }

    private function getUltimoPeriodoVacacionesEmpleado()
    {
        $maximoPeriodo = $this->empleado->getVacacionesEmpleado()->max("periodo");
        if(empty($maximoPeriodo))
        {
            $maximoPeriodo = date('Y');
        }
        return $maximoPeriodo;

    }


    private function setEmpleadoFromMovimientosVacaciones()
    {
        $this->empleado = new Empleado();
        $atributosEmpleado = $this->movimientoVacaciones->getAttributes(["compania","codigo_empleado"]);
        $this->empleado->setAttributes($atributosEmpleado);

    }

    /**
     * @return MovimientoVacaciones
     */
    public function getMovimientoVacaciones(): MovimientoVacaciones
    {
        return $this->movimientoVacaciones;
    }

    /**
     * @return VacacionesEmpleado[]
     */
    public function getVacacionesEmpleado(): array
    {
        return $this->vacacionesEmpleado;
    }

    /**
     * @param VacacionesEmpleado[] $vacacionesEmpleado
     */
    public function setVacacionesEmpleado(array $vacacionesEmpleado)
    {
        $this->vacacionesEmpleado = $vacacionesEmpleado;
    }





    /**
     * @return Empleado
     */
    public function getEmpleado(): Empleado
    {
        return $this->empleado;
    }

    /**
     * @param Empleado $empleado
     */
    public function setEmpleado(Empleado $empleado)
    {
        $this->empleado = $empleado;
    }

    public function guardarVacacionesEmpleado()
    {
        $vacacionEmpleadoUltimo = end($this->vacacionesEmpleado);
        $diasHabiles = $this->movimientoVacaciones->getAttribute('dias_habiles');
        foreach ($this->vacacionesEmpleado as $vacacionEmpleado)
        {
            $ultimo = $vacacionEmpleado==$vacacionEmpleadoUltimo;
            $vacacionEmpleadoMovimiento = $this->restarVacacionesPeriodo($diasHabiles,$vacacionEmpleado,$ultimo);
            $diasHabiles = $diasHabiles-$vacacionEmpleadoMovimiento->getAttribute('dias_disfrutado_movimiento');
            $vacacionEmpleadoMovimiento->save();
        }

    }




    /**
     * @param double $diasHabiles
     * @param VacacionesEmpleado $vacacionEmpleado
     * @return VacacionEmpleadoMovimiento
     */

    private function restarVacacionesPeriodo(float $diasHabiles,VacacionesEmpleado $vacacionEmpleado,bool $contarNegativos = false):VacacionEmpleadoMovimiento
    {
        $vacacionEmpleadoMovimiento = $this->getVacacionEmpleadoMovimiento($vacacionEmpleado);
        $diasDisponibles = $vacacionEmpleado->getAttribute('dias_disponibles');
        $calculoDias = $this->hacerCalculoVacaciones($diasHabiles,$diasDisponibles,$contarNegativos);
        $vacacionEmpleadoMovimiento->setAttribute('dias_disponibles',$calculoDias[1]);
        $vacacionEmpleadoMovimiento->setAttribute('dias_disfrutado_movimiento',$calculoDias[0]);
        return $vacacionEmpleadoMovimiento;
    }

    /**
     * @param float $diasHabiles
     * @param float $diasDisponibles
     * @param bool $contarNegativos
     * @return array
     * Se retorna un array:
     *  En la posicion 0 se encuentran los dias que se van a disfrutar de ese periodo
     *  En la posicion 1 se encuentran los dias disponibles que quedan de ese movimiento
     *
     */

    private function hacerCalculoVacaciones(float $diasHabiles,float $diasDisponibles,bool $contarNegativos = false):array
    {
        $diasRestantesADisfrutar = $diasDisponibles-$diasHabiles;
        $diasADisfrutar = $diasRestantesADisfrutar>0?$diasHabiles:$diasDisponibles;
        if(!$contarNegativos)
        {
            $diasRestantesADisfrutar = $diasRestantesADisfrutar>=0?$diasRestantesADisfrutar:0;
        }
        return [$diasADisfrutar,$diasDisponibles];


    }


    /**
     * @param VacacionesEmpleado $vacacionEmpleado
     * @return VacacionEmpleadoMovimiento
     */
    private function getVacacionEmpleadoMovimiento(VacacionesEmpleado $vacacionEmpleado) : VacacionEmpleadoMovimiento
    {
        $pksMovimientoVacaciones = $this->movimientoVacaciones->getPrimaryKey();
        $pksVacacionEmpleado = $vacacionEmpleado->getPrimaryKey();
        $vacacionEmpleadoMovimiento = new VacacionEmpleadoMovimiento();
        $diasDisponibles = $vacacionEmpleado->getAttribute('dias_disponibles');
        $diasDisfrutados = $vacacionEmpleado->getAttribute('dias_disfrutados');
        $vacacionEmpleadoMovimiento->setAttributes(array_merge($pksMovimientoVacaciones,$pksVacacionEmpleado));
        $vacacionEmpleadoMovimiento->setAttribute('dias_disponibles',$diasDisponibles);
        $vacacionEmpleadoMovimiento->setAttribute('dias_disfrutados',$diasDisfrutados);
        return $vacacionEmpleadoMovimiento;


    }








}