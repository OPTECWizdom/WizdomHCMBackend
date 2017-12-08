<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 09/11/2017
 * Time: 16:40
 */

namespace backend\models\movimientosVacaciones\vacacionesDiasCalculator;
use backend\models\movimientosVacaciones\MovimientoVacaciones;
use backend\models\empleado\Empleado;

class DiasFeriadosCalculator implements IDiasVacacionesCalculator
{
    /**
     * @var MovimientoVacaciones $movimientoVacaciones
     */

    private $movimientoVacaciones;
    /**
     */
    public function calcularVacaciones()
    {
        return ['dias_feriados'=>$this->calcularDiasFeriados()];


    }

    private function calcularDiasFeriados()
    {
        $fechaInicial = new \DateTime($this->movimientoVacaciones->getAttribute('fecha_inicial'));
        $fechaFinal = new \DateTime($this->movimientoVacaciones->getAttribute('fecha_final'));
        $diasFeriados = $this->getDiasFeriados();
        $diasFeriadosCount = 0;
        if(!empty($diasFeriados))
        {
            foreach ($diasFeriados as $diaFeriado){
                $mes = $diaFeriado->getAttribute('numero_mes_feriado');
                $dia = $diaFeriado->getAttribute('numero_dia_feriado');
                $anoInicial =  intval($fechaInicial->format('Y'));
                $anoFinal = intval($fechaFinal->format('Y'));
                for($ano = $anoInicial;$ano<=$anoFinal;$ano++)
                {
                    $fechaFeriado = new \DateTime("$ano-$mes-$dia");
                    if($fechaInicial<=$fechaFeriado && $fechaFeriado<=$fechaFinal)
                    {
                        $diasFeriadosCount++;
                    }
                }
            }
        }
        return $diasFeriadosCount;

    }


    /**
     * @return \yii\db\ActiveRecord
     */

    private function getDiasFeriados()
    {
        $empleado = new Empleado();
        $empleado->setAttributes($this->movimientoVacaciones->getAttributes(Empleado::primaryKey()));
        return $empleado->getDiasFeriados();


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