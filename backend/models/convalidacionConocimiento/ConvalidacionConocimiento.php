<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 04/04/2018
 * Time: 16:50
 */
namespace backend\models\convalidacionConocimiento;

use backend\models\procesoModelConnector\IProcesoSubject;
use yii\db\ActiveRecord;
use backend\models\abstractWizdomModel\AbstractWizdomModel;

class ConvalidacionConocimiento extends AbstractWizdomModel implements  IProcesoSubject
{
    public static function tableName()
    {
        return 'CONVALIDACION_CONOCIMIENTO';
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

    public function getNotificationSubject()
    {
        return '';
    }
    public function getSubjectProcesoDescription()
    {
        return '';
    }


}