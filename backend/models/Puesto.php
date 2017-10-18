<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 18/10/2017
 * Time: 10:59
 */

namespace app\models;


use yii\db\ActiveRecord;

class Puesto extends ActiveRecord
{
    public static function tableName()
    {
        return "puesto_compania";
    }

    public static function primaryKey()
    {
        return ["compania","codigo_puesto"];
    }

    public function rules()
    {
        return [
            [
                ["compania","codigo_puesto"],"required"
            ],
            [
                ["codigo_clasificacion","nombre_puesto","descripcion_puesto","tstamp"],"string"
            ]

        ];
    }

}