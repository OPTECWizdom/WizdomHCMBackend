<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 09/11/2017
 * Time: 14:06
 */

namespace backend\models\calendario\diaFeriado;


use yii\db\ActiveRecord;

class DiaFeriado extends ActiveRecord implements IDiaFeriado
{
    public static function tableName()
    {
        return  'DIAS_FERIADOS';
    }

    public static function primaryKey()
    {
       return ['compania','numero_mes_feriado','numero_dia_feriado'];
    }

    public function getDiaFeriado()
    {
        return $this->numero_dia_feriado;
    }

    public function getMesFeriado()
    {
        return $this->numero_mes_feriado;
    }

}