<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 14/11/2017
 * Time: 10:49
 */

namespace backend\models;


class EventoCalendarioDiaFeriado extends EventoCalendarioAbstract
{
    public static function tableName()
    {
        return "dias_feriados";
    }

    public static function primaryKey()
    {
        ['compania','numero_mes_feriado','numero_dia_feriado'];
    }

    public  function getCompania()
    {
        return $this->compania;
    }

    public  function getIdEvento()
    {
        return $this->compania."-".$this->numero_mes_feriado."-".$this->numero_dia_feriado;
    }

    public  function getModulo()
    {
        return "VAC";
    }

    public  function getFechaInicial()
    {
        $fechaInicial = new \DateTime(date('Y').'-'.$this->numero_mes_feriado.'-'.$this->numero_dia_feriado);
        return $fechaInicial->format('Y-m-d');

    }

    public  function getFechaFinal()
    {
        $fechaFinal= new \DateTime(date('Y').'-'.$this->numero_mes_feriado.'-'.$this->numero_dia_feriado);
        return $fechaFinal->format('Y-m-d');

    }

    public  function getTitulo()
    {
        return $this->nombre_dia_feriado;
    }


    public  function getTipoDeEvento()
    {
        return "FER";
    }

    public  function getHoraInicial()
    {
        return "00:00:00";
    }

    public  function getHoraFinal()
    {
        return "23:59:59";
    }

    public  function getTstamp()
    {
        return $this->tstamp;
    }

}