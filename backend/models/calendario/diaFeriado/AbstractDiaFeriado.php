<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 09/01/2018
 * Time: 9:29
 */

namespace backend\models\calendario\diaFeriado;


use backend\models\eventoCalendario\EventoCalendarioAbstract;

abstract class AbstractDiaFeriado extends EventoCalendarioAbstract
{

    public abstract function getDiaFeriado();

    public abstract function getMesFeriado();

    public abstract function getNombreDiaFeriado();


    public  function getCompania()
    {
        return $this->compania;
    }

    public  function getIdEvento()
    {
        return $this->compania."-".$this->getMesFeriado()."-".$this->getDiaFeriado();
    }

    public  function getModulo()
    {
        return "VAC";
    }

    public  function getFechaInicial()
    {
        $fechaInicial = new \DateTime(date('Y').'-'.$this->getMesFeriado().'-'.$this->getDiaFeriado());
        return $fechaInicial->format('Y-m-d H:i:s');

    }

    public  function getFechaFinal()
    {
        $fechaFinal= new \DateTime(date('Y').'-'.$this->getMesFeriado().'-'.$this->getDiaFeriado());
        return $fechaFinal->format('Y-m-d H:i:s');

    }

    public  function getTitulo()
    {
        return $this->getNombreDiaFeriado();
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




    public function fields()
    {
        $parentFields = parent::fields();
        $fields['numero_dia_feriado']='diaFeriado';
        $fields['numero_mes_feriado']='mesFeriado';
        $fields['nombre_dia_feriado']='nombreDiaFeriado';
        return array_merge($parentFields,$fields);

    }

}