<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 26/10/2017
 * Time: 17:14
 */

namespace backend\models\procesoModelConnector\procesoConvalidacionConocimiento;


use backend\models\convalidacionConocimiento\ConvalidacionConocimiento;
use yii\db\ActiveRecord;
use backend\models\procesoModelConnector\IProcesoSubjectConnector;
use backend\models\proceso\Proceso;
use backend\models\movimientosVacaciones\MovimientoVacaciones;
use backend\models\proceso\ProcesoDetalle;

class ProcesoConvalidacionConocimiento extends ActiveRecord implements IProcesoSubjectConnector
{

    public static function tableName()
    {
        return "PROCESO_CONVALIDACION_CONOCIMIENTO";
    }

    public static function primaryKey()
    {
        return ["compania","codigo_empleado","consecutivo"];
    }


    public function rules()
    {
        return [
            [
                ["compania","codigo_empleado","consecutivo"],"required"
            ],
            [
                ["compania","codigo_empleado","tipo_flujo_proceso"],"string"
            ],
            [
                ["consecutivo","id_proceso"],"integer"
            ]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProceso()
    {
        return $this->hasOne(Proceso::className(),["compania"=>"compania","tipo_flujo_proceso"=>"tipo_flujo_proceso",
            "id_proceso"=>"id_proceso"]);
    }

    public function getProcesoDetalle()
    {
        return $this->hasOne(ProcesoDetalle::className(),["compania"=>"compania","tipo_flujo_proceso"=>"tipo_flujo_proceso",
            "id_proceso"=>"id_proceso"]);
    }

    /**
     *
     */
    public function getConvalidacionConocimiento()
    {
        return $this->hasOne(ConvalidacionConocimiento::className(),["compania"=>"compania","codigo_empleado"=>"codigo_empleado",
            "consecutivo"=>'consecutivo']);

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

    public function extraFields()
    {
        return [
            'procesoDetalle'
        ];
    }

    public function getProcesoSubject()
    {
        return $this->getConvalidacionConocimiento()->one();
    }


}