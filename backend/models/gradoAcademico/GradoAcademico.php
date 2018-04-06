<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 06/04/2018
 * Time: 15:48
 */

namespace backend\models\gradoAcademico;


use yii\db\ActiveRecord;

class GradoAcademico extends ActiveRecord
{
    public static function tableName()
    {
        return "GRADO_ACADEMICO";
    }

    public static function primaryKey()
    {
        return ['grado_academico'];
    }

}