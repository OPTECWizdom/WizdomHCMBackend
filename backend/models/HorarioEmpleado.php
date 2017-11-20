<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 19/10/2017
 * Time: 14:39
 */

namespace backend\models;


use yii\db\ActiveRecord;

class HorarioEmpleado extends ActiveRecord
{
    public static function tableName()
    {
        return "HORARIO_EMPLEADO";
    }
    public static function primaryKey()
    {
        return ["compania","codigo_empleado","consecutivo","codigo_horario"];
    }

    public function rules()
    {
        return [
            [
                ["compania","codigo_horario","codigo_empleado","consecutivo"],"required"
            ]

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */

    public function getEmpleado()
    {
        return $this->hasOne(Empleado::className(),['compania'=>'compania','codigo_empleado'=>'codigo_empleado']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */

    public function getHorario()
    {
        return $this->hasOne(Horario::className(),['compania'=>'compania','codigo_horario'=>'codigo_horario']);
    }




}