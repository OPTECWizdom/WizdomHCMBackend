<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 19/10/2017
 * Time: 14:24
 */

namespace backend\models\horario;


use yii\db\ActiveRecord;
use backend\models\horario\detalleHorario\DetalleHorario;

class Horario extends ActiveRecord
{
    public static function tableName()
    {
        return "HORARIO";
    }

    public static function primaryKey()
    {
        return ["compania","codigo_horario"];
    }

    public function rules(){
        return [
                    [
                        ["compania","codigo_horario"],"required"

                    ]

            ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */


    public function getDetalleHorario()
    {
        return $this->hasMany(DetalleHorario::className(),['compania'=>'compania','codigo_horario'=>'codigo_horario']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiasTrabajo()
    {
        return $this->getDetalleHorario()->where(['!=','descanso','S'])->select(['dia_semana']);

    }

    public function extraFields()
    {
        return ["diasTrabajo"];
    }


    public function fields()
    {
        $fields = parent::fields();
        return $fields;

    }

    public function isExcepcion()
    {
        $excepcion = $this->hasOne(ExcepcionHorario::className(),["compania"=>"compania","codigo_horario"=>"codigo_horario"])->all();
        return !empty($excepcion);
    }


}