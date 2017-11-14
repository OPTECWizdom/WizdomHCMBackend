<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 14/11/2017
 * Time: 10:00
 */

namespace backend\models;


class EventoCalendarioMovimientoVacacion extends EventoCalendarioAbstract
{

    public static function tableName()
    {
        return 'movimientos_vacaciones';
    }

    public static function primaryKey()
    {
        return ['compania','tipo_mov','consecutivo_movimiento'];
    }

    public function getCompania()
    {
        return $this->compania;
    }
    public function getIdEvento()
    {
        return $this->consecutivo_movimiento;
    }
    public function getModulo()
    {
        return 'VAC';
    }
    public function getFechaInicial()
    {
        return $this->fecha_inicial;
    }

    public function getFechaFinal()
    {
        return $this->fecha_final;
    }
    public function getTitulo()
    {
        return "Vacaciones";
    }

    public function getTipoDeEvento()
    {
        return 'VAC-'.$this->estado;
    }
    public function getHoraInicial()
    {
        return "00:00:00";
    }
    public function getHoraFinal()
    {
        return "23:59:59";
    }

    public function getTstamp()
    {
        return $this->tstamp;
    }


}