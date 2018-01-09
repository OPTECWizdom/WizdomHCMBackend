<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 14/11/2017
 * Time: 10:31
 */

namespace backend\models\eventoCalendario;


use yii\db\ActiveRecord;

abstract class EventoCalendarioAbstract extends ActiveRecord
{


    public abstract function getCompania();

    public abstract function getIdEvento();

    public abstract function getModulo();

    public abstract function getFechaInicial();

    public abstract function getFechaFinal();

    public abstract function getTitulo();


    public abstract function getTipoDeEvento();

    public abstract function getHoraInicial();

    public abstract function getHoraFinal();

    public abstract function getTstamp();


    public function fields()
    {
        return
        [
            'compania','idEvento','modulo','fechaInicial','titulo','fechaFinal',
            'tipoDeEvento','horaInicial','horaFinal','tstamp'
        ];
    }




}