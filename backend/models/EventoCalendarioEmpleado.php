<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 14/11/2017
 * Time: 9:55
 */

namespace backend\models;


use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class EventoCalendarioEmpleado extends  ActiveRecord
{

    public static function tableName()
    {
        return 'EMPLEADO';
    }


    public static  function primaryKey()
    {
        return ['compania','codigo_empleado'];
    }

    public function rules()
    {
        return [
            [
                ['compania','codigo_empleado'],'required'
            ]
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */

    public function getOrganigrama()
    {
        return $this->hasOne(Organigrama::className(),['compania'=>'compania','codigo_nodo_organigrama'=>'codigo_nodo_organigrama']);
    }

    /**
     * @return ActiveQuery
     */

    public function getMovimientosVacaciones()
    {
        return $this->hasMany(EventoCalendarioMovimientoVacacion::className(),['compania'=>'compania',
                                                                                'codigo_empleado'=>'codigo_empleado'])
                                                                                ->where("estado not in ('B','R')");
    }
    /**
     * @return ActiveRecord
     */

    public function getDiasFeriados()
    {

        /**
         * @var Organigrama $organigrama
         **/
        $organigrama = $this->getOrganigrama()->one();
        if(!empty($organigrama))
        {
            $catalogoDiasFeriados = $organigrama->getAttribute('catalogo_dias_feriados');
            if(!empty($catalogoDiasFeriados))
            {
                return EventoCalendarioDiaFeriadoCatalogo::find()->where(['compania'=>$this->compania,'catalogo_dias_feriados'=>$catalogoDiasFeriados])->all();
            }
        }
        return EventoCalendarioDiaFeriado::find()->where(["compania"=>$this->getAttribute('compania')])->all();
    }


    public function fields()
    {
        return [
            'movimientosVacaciones',
            'diasFeriados'
        ];
    }


}