<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 01/09/2017
 * Time: 11:56
 */

namespace backend\models;


use yii\db\ActiveRecord;

class FlujoTipoProcesoCorreoExterno extends ActiveRecord
{
    public static function tableName()
    {
        return "FLUJO_TIPO_PROCESO_CORREO_EXT";
    }


    public static function primaryKey()
    {
        return [
            "compania","tipo_flujo_proceso","codigo_tarea"
        ];
    }




}