<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 04/04/2018
 * Time: 16:50
 */
namespace backend\models\convalidacionConocimiento;

use backend\models\empleado\Empleado;
use backend\models\procesoModelConnector\IProcesoSubject;
use yii\db\ActiveRecord;
use backend\models\abstractWizdomModel\AbstractWizdomModel;

class ConvalidacionConocimiento extends AbstractWizdomModel implements  IProcesoSubject
{
    public static function tableName()
    {
        return 'CONVALIDACION_CONOCIMIENTO';
    }

    public static function primaryKey()
    {
        return ["compania","codigo_empleado","consecutivo"];
    }

    public function getExceptionHandler()
    {

    }
    public function rules()
    {
        return
            [
                [
                    ['compania','codigo_empleado',],'required',
                ],
                [
                    [
                        'compania','codigo_empleado','conocimiento_desc','categoria_conocimiento',
                        'fuente_conocimiento','nivel_conocimiento','capacitador','codigo_tipo','codigo_especialidad',
                        'codigo_modalidad','lugar','observacion','convalidado','tstamp','fecha'
                    ],
                    'string'
                ],

                [
                    ['horas_curso','consecutivo'],"integer"
                ]
            ];
    }
    public function behaviors()
    {
        return [

            [
                'class' => 'common\components\mdmsoft\autonumber\Behavior',
                'attribute' => 'consecutivo', // required

            ],
            'timestamp' => [
                'class' => 'backend\behaviors\TimestampStringBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['tstamp'],
                ]
            ],


        ];

    }

    /**
     * @return \yii\db\ActiveQuery
     */

    public function getEmpleado()
    {
        return $this->hasOne(Empleado::className(),["compania"=>"compania","codigo_empleado"=>"codigo_empleado"]);

    }

    public function getNotificationSubject()
    {
        $empleadoNombre = $this->getEmpleado()->one()->getNombreCompleto();
        return 'Convalidación de conocimiento - '.mb_convert_case(mb_strtolower($empleadoNombre),MB_CASE_TITLE).' - '.$this->conocimiento_desc;
    }
    public function getSubjectProcesoDescription()
    {
        return 'Convalidación de conocimiento - '.$this->conocimiento_desc;
    }


}