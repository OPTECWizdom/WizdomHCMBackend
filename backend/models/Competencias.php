<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 18/08/2017
 * Time: 11:06
 */

namespace backend\models;


class Competencias extends \yii\db\ActiveRecord
{



    public static function primaryKey()
    {
        return ['compania','codigo_competencia'];
    }


}