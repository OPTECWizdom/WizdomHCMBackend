<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 01/09/2017
 * Time: 11:56
 */

namespace app\models;


use yii\db\ActiveRecord;

class FlujoTipoProcesoCorreoExterno extends ActiveRecord
{
    public static function tableName()
    {
        return "flujo_tipo_proceso_correo_externo";
    }


    public static function primaryKey()
    {
        return [
            "compania","tipo_flujo_proceso","codigo_tarea"
        ];
    }




}