<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 14/11/2017
 * Time: 9:55
 */

namespace backend\models\eventoCalendario;


use backend\models\calendario\diaFeriado\diasFeriadosSelector\DiaFeriadoSelectorManager;
use backend\models\empleado\Empleado;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class EventoCalendarioEmpleado extends  Empleado
{


    /**
     * @return ActiveQuery
     */

    public function getMovimientosVacaciones()
    {
        return $this->hasMany(EventoCalendarioMovimientoVacacion::className(),['compania'=>'compania',
                                                                                'codigo_empleado'=>'codigo_empleado'])
                                                                                ->where("estado not in ('B','R')")
                                                                                ->andWhere(['tipo_mov'=>'DVA']);
    }
    /**
     * @return ActiveRecord[]
     */

    public function getDiasFeriados()
    {
        $diaSelectorManager =  new DiaFeriadoSelectorManager();
        return $diaSelectorManager->getDiasFeriado($this);



    }


    public function fields()
    {
        return [
            'movimientosVacaciones',
            'diasFeriados'
        ];
    }


}