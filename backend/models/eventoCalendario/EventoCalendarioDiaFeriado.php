<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 14/11/2017
 * Time: 10:49
 */

namespace backend\models\eventoCalendario;


class EventoCalendarioDiaFeriado extends EventoCalendarioAbstract
{



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
        return $fechaInicial->format('Y-m-d H:i:s');

    }

    public  function getFechaFinal()
    {
        $fechaFinal= new \DateTime(date('Y').'-'.$this->numero_mes_feriado.'-'.$this->numero_dia_feriado);
        return $fechaFinal->format('Y-m-d H:i:s');

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
        return "10:00:00";
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