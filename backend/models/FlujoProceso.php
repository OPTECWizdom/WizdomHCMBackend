<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 21/08/2017
 * Time: 12:13
 */

namespace app\models;


class FlujoProceso extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'flujo_proceso';
    }

    public static function primaryKey()
    {
        return ['compania','id_proceso','tipo_flujo_proceso','codigo_tarea'];
    }

    public function behaviors()
    {
        return [
            [
                'class' => '\raoul2000\workflow\base\SimpleWorkflowBehavior',
                'statusAttribute' => 'estado'

            ]
        ];

    }




}