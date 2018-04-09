<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 09/04/2018
 * Time: 15:51
 */

namespace backend\models\procesoModelConnector\procesoSolicitudesConstancias;


use backend\models\proceso\Proceso;
use backend\models\proceso\ProcesoDetalle;
use backend\models\procesoModelConnector\IProcesoSubjectConnector;
use backend\models\solicitudesConstancias\SolicitudConstancia;
use yii\db\ActiveRecord;

class ProcesoSolicitudesConstancias extends ActiveRecord implements IProcesoSubjectConnector
{
    public static function tableName()
    {
        return "PROCESO_SOLICITUDES_CONSTANCIAS";
    }

    public static function primaryKey()
    {
        return ["compania","consecutivo","empleado"];
    }


    public function rules()
    {
        return [
            [
                ["compania","consecutivo","empleado"],"required"
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
    public function getSolicitudConstancia()
    {
        return $this->hasOne(SolicitudConstancia::className(),["compania"=>"compania","empleado"=>"empleado",
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
        return $this->getSolicitudConstancia()->one();
    }

}