<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 09/04/2018
 * Time: 16:11
 */

namespace backend\models\documentos;


use yii\db\ActiveRecord;

class Documento extends ActiveRecord
{
    public static function tableName()
    {
        return "DOCUMENTOS";
    }

    public static function primaryKey()
    {
        return ["compania","codigo_documento"];
    }

}