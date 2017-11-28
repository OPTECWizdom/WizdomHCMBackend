<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 14/11/2017
 * Time: 10:59
 */

namespace backend\models;


class EventoCalendarioDiaFeriadoCatalogo extends EventoCalendarioAbstract
{

    public static function tableName()
    {
        return "DIAS_FERIADOS_X_CATALOGO";
    }

    public static function primaryKey()
    {
        ['compania','catalogo_dias_feriados','mes','dia'];
    }

    public  function getCompania()
    {
        return $this->compania;
    }

    public  function getIdEvento()
    {
        return $this->compania."-".$this->catalogo_dias_feriados."-".$this->mes."-".$this->dia;
    }

    public  function getModulo()
    {
        return "VAC";
    }

    public  function getFechaInicial()
    {
        $fechaInicial =  new \DateTime(date('Y').'-'.$this->mes.'-'.$this->dia);
        return $fechaInicial->format('Y-m-d');

    }

    public  function getFechaFinal()
    {
        $fechaFinal =  new \DateTime(date('Y').'-'.$this->mes.'-'.$this->dia);
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