<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 19/10/2017
 * Time: 14:24
 */

namespace app\models;


use yii\db\ActiveRecord;

class Horario extends ActiveRecord
{
    public static function tableName()
    {
        return "horario";
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


    public function getDetalleHorario()
    {
        return $this->hasMany(DetalleHorario::className(),['compania'=>'compania','codigo_horario'=>'codigo_horario']);
    }

}