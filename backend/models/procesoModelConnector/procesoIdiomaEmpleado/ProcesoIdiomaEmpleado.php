<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 26/10/2017
 * Time: 17:14
 */

namespace backend\models\procesoModelConnector\procesoIdiomaEmpleado;


use backend\models\idioma\idiomaEmpleado\IdiomaEmpleado;
use yii\db\ActiveRecord;
use backend\models\procesoModelConnector\IProcesoSubjectConnector;
use backend\models\proceso\Proceso;
use backend\models\proceso\ProcesoDetalle;

class ProcesoIdiomaEmpleado extends ActiveRecord implements IProcesoSubjectConnector
{

    public static function tableName()
    {
        return "PROCESO_IDIOMA_EMPLEADO";
    }

    public static function primaryKey()
    {
        return ["compania","codigo_empleado","idioma"];
    }


    public function rules()
    {
        return [
            [
                ["compania","codigo_empleado","idioma"],"required"
            ],
            [
                ["compania","idioma","tipo_flujo_proceso","tstamp"],"string"
            ],
            [
                ["id_proceso"],"integer"
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
    public function getIdiomaEmpleado()
    {
        return $this->hasOne(IdiomaEmpleado::className(),["compania"=>"compania","codigo_empleado"=>"codigo_empleado",
            "idioma"=>'idioma']);

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
        return $this->getIdiomaEmpleado()->one();
    }


}