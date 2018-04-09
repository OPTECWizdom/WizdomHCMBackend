<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 04/04/2018
 * Time: 16:50
 */
namespace backend\models\preparacionAcademicaEmpleado;

use backend\models\empleado\Empleado;
use backend\models\gradoAcademico\GradoAcademico;
use backend\models\procesoModelConnector\IProcesoSubject;
use backend\models\profesion\Profesion;
use yii\base\ModelEvent;
use yii\db\ActiveRecord;
use backend\models\abstractWizdomModel\AbstractWizdomModel;

class PreparacionAcademicaEmpleado extends AbstractWizdomModel implements  IProcesoSubject
{
    const EVENT_BEFORE_CONVALIDADO = 'EVENT_BEFORE_CONVALIDADO';


    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $this->on(self::EVENT_BEFORE_UPDATE,[$this,"beforeUpdate"]);
    }

    public static function tableName()
    {
        return 'PREPARACION_ACADEMICA_EMPLEADO';
    }

    public static function primaryKey()
    {
        return ["compania", "codigo_empleado", "consecutivo"];
    }

    public function getExceptionHandler()
    {

    }

    public function rules()
    {
        return
            [
                [
                    ['compania', 'codigo_empleado'], 'required',
                ],
                [
                    [
                        'compania', 'codigo_empleado', 'codigo_profesion', 'grado_academico', 'nombre_de_institucion',
                        'lugar', 'fecha', 'observaciones', 'estado',
                        'ind_acreditado_carrera', 'convalidado', 'tstamp','fecha_convalidacion'
                    ],
                    'string'
                ],
                [
                    ['consecutivo'],'integer'
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['fecha_convalidacion'],
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
        return $this->hasOne(Empleado::className(), ["compania" => "compania", "codigo_empleado" => "codigo_empleado"]);

    }


    public function getNotificationSubject()
    {
        $empleadoNombre = $this->getEmpleado()->one()->getNombreCompleto();
        $nombreProfesion = $this->getProfesion()->one()->getAttribute('descripcion_profesion');
        $gradoAcademico = $this->getGradoAcademico()->one()->getAttribute('descripcion_grado');
        return 'Convalidación de Preparación Académica - ' .
            mb_convert_case(mb_strtolower($empleadoNombre), MB_CASE_TITLE) . ' - ' ." $gradoAcademico $nombreProfesion";
    }

    public function getSubjectProcesoDescription()
    {
        $nombreProfesion = $this->getProfesion()->one()->getAttribute('descripcion_profesion');
        $gradoAcademico = $this->getGradoAcademico()->one()->getAttribute('descripcion_grado');
        return 'Convalidación de Preparación Académica - ' ." $gradoAcademico $nombreProfesion";
    }

    public function beforeUpdate()
    {
        if($this->convalidado=='A')
        {
            $event = new ModelEvent();
            $this->trigger(self::EVENT_BEFORE_CONVALIDADO,$event);
        }

    }

    public function getProfesion()
    {
        return $this->hasOne(Profesion::className(),["codigo_profesion"=>"codigo_profesion"]);
    }

    public function getGradoAcademico()
    {
        return $this->hasOne(GradoAcademico::className(),["grado_academico"=>"grado_academico"]);
    }



}