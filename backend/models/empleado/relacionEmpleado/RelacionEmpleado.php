<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 10/11/2017
 * Time: 11:29
 */

namespace backend\models\empleado\relacionEmpleado;


use yii\db\ActiveRecord;
use backend\models\empleado\Empleado;

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

    /**
     * @return \yii\db\ActiveQuery
     */

    public function getEmpleado()
    {
        return $this->hasOne(Empleado::className(),["compania"=>"compania","codigo_empleado"=>"codigo_empleado_relacion"]);
    }

}