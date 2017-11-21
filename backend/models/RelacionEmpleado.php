<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 10/11/2017
 * Time: 11:29
 */

namespace backend\models;


use yii\db\ActiveRecord;

class RelacionEmpleado extends ActiveRecord
{


    public static  function tableName()
    {
        return 'RELACIONES_EMPLEADO';
    }


    public static function primaryKey()
    {
        return ['compania','tipo_relacion','codigo_empleado'];
    }


    public function rules()
    {
        return [
            [
                ['compania','tipo_relacion','codigo_empleado','codigo_empleado_relacion'],
                'string'
            ]

        ];
    }

    public function behaviors()
    {
        return [

            'timestamp' => [
                'class' => 'backend\behaviors\TimestampStringBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['tstamp'],
                ]
            ]

        ];

    }

}