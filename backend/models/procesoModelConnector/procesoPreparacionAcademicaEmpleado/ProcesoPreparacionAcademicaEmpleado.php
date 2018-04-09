<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 26/10/2017
 * Time: 17:14
 */

namespace backend\models\procesoModelConnector\procesoPreparacionAcademicaEmpleado;


use backend\models\preparacionAcademicaEmpleado\PreparacionAcademicaEmpleado;
use yii\db\ActiveRecord;
use backend\models\procesoModelConnector\IProcesoSubjectConnector;
use backend\models\proceso\Proceso;
use backend\models\proceso\ProcesoDetalle;

class ProcesoPreparacionAcademicaEmpleado extends ActiveRecord implements IProcesoSubjectConnector
{

    public static function tableName()
    {
        return "PROCESO_PREPARACION_ACADEMICA_EMPLEADO";
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
                ["compania","tipo_flujo_proceso","tstamp"],"string"
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
     * @return \yii\db\ActiveQuery
     */
    public function getPreparacionAcademicaEmpleado()
    {
        return $this->hasOne(PreparacionAcademicaEmpleado::className(),["compania"=>"compania","codigo_empleado"=>"codigo_empleado",
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
        return $this->getPreparacionAcademicaEmpleado()->one();
    }


}