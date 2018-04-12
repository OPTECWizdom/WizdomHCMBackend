<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 04/04/2018
 * Time: 16:50
 */
namespace backend\models\solicitudesConstancias;

use backend\models\documentos\Documento;
use backend\models\empleado\Empleado;
use backend\models\gradoAcademico\GradoAcademico;
use backend\models\procesoModelConnector\IProcesoSubject;
use backend\models\profesion\Profesion;
use yii\base\ModelEvent;
use yii\db\ActiveRecord;
use backend\models\abstractWizdomModel\AbstractWizdomModel;

class SolicitudConstancia extends AbstractWizdomModel implements  IProcesoSubject
{




    public static function tableName()
    {
        return 'SOLICITUDES_CONSTANCIAS';
    }

    public static function primaryKey()
    {
        return ["compania", "consecutivo","empleado"];
    }

    public function getExceptionHandler()
    {

    }

    public function rules()
    {
        return
            [
                [
                    ['compania', 'empleado'], 'required',
                ],
                [
                    [
                        'compania', 'empleado', 'fecha_solicitud', 'fecha_esperada', 'estado',
                        'dirigido_a', 'estado', 'observaciones', 'tstamp',
                    ],
                    'string'
                ],
                [
                    ['consecutivo','nomina_permanente','codigo_documento',],'integer'
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
            ],
            'fecha_convalidacion' => [
                'class' => 'backend\behaviors\TimestampStringBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['fecha_solicitud'],
                ]
            ],
            [
                'class' => 'common\components\mdmsoft\autonumber\Behavior',
                'attribute' => 'consecutivo', // required

            ]


        ];

    }

    /**
     * @return \yii\db\ActiveQuery
     */

    public function getEmpleado()
    {
        return $this->hasOne(Empleado::className(), ["compania" => "compania", "codigo_empleado" => "empleado"]);

    }


    public function getNotificationSubject()
    {
        $empleadoNombre = $this->getEmpleado()->one()->getNombreCompleto();
        $nombreDocumento = $this->getDocumento()->one()->getAttribute('descripcion');
        return 'Solicitud de Constancia - ' .
            mb_convert_case(mb_strtolower($empleadoNombre), MB_CASE_TITLE) . ' - ' ." $nombreDocumento";
    }

    public function getSubjectProcesoDescription()
    {
        $nombreDocumento = $this->getDocumento()->one()->getAttribute('descripcion');
        return 'Solicitud de Constancia - ' ." $nombreDocumento";
    }







    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumento()
    {
        return $this->hasOne(Documento::className(),['compania'=>'compania','codigo_documento'=>'codigo_documento']);
    }



    public function attributes()
    {
        $fields = ['codigo_empleado',$this->empleado];

        return array_combine(parent::attributes(),$fields);
    }


}