<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 10/01/2018
 * Time: 10:41
 */

namespace backend\models\horario;


use backend\models\abstractWizdomModel\AbstractWizdomModel;
use yii\db\ActiveRecord;

class ExcepcionHorario extends AbstractWizdomModel
{

    public function getExceptionHandler()
    {
        return null;
    }


    public static function tableName()
    {
        return "excepcion_horario";
    }

    public static function primaryKey()
    {
        return ["compania","codigo_horario"];
    }


}