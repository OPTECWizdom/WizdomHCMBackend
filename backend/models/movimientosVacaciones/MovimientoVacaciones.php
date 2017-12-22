<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 22/08/2017
 * Time: 9:42
 */

namespace backend\models\movimientosVacaciones;


use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use backend\models\{
    abstractWizdomModel\AbstractWizdomModel,
    movimientosVacaciones\exceptions\MovimientosVacacionesExceptionHandler,
    procesoModelConnector\IProcesoSubject, empleado\Empleado,
    movimientosVacaciones\vacacionesEmpleadoMovimiento\VacacionEmpleadoMovimiento,
    movimientosVacaciones\vacacionesDiasCalculator\GeneralVacacionesCalculator,
    movimientosVacaciones\vacacionesEmpleadoMovimiento\VacacionesEmpleadoMovimientoHelper,
    movimientosVacaciones\vacacionesDiasCalculator\VacacionesCalculatorFactory,
    procesoModelConnector\procesoMovimientoVacacion\ProcesoMovimientoVacacion,
    movimientosVacaciones\controlAjusteVacacionesMovimiento\ControlAjusteVacacionesMovimiento
};

class MovimientoVacaciones extends AbstractWizdomModel implements IProcesoSubject
{

    protected function getExceptionHandler()
    {
        return new MovimientosVacacionesExceptionHandler($this);
    }


    public function init()
    {
        parent::init();
        $this->on(self::EVENT_BEFORE_INSERT,[$this,'setDefaultValues']);
        $this->on(self::EVENT_BEFORE_INSERT,[$this,'calcularDesgloseVacaciones']);
        $this->on(self::EVENT_AFTER_INSERT,[$this,'guardarDesgloseVacaciones']);

    }

    public static function tableName()
    {
        return "MOVIMIENTOS_VACACIONES";
    }

    public static function primaryKey()
    {
        return ['compania','tipo_mov','consecutivo_movimiento'];
    }

    public function behaviors()
    {
        return [
            [
                'class' => '\raoul2000\workflow\base\SimpleWorkflowBehavior',
                'statusAttribute' => 'estado_flujo_proceso'

            ],
            [
                'class' => 'common\components\mdmsoft\autonumber\Behavior',
                'attribute' => 'consecutivo_movimiento', // required

            ],
            'timestamp' => [
                'class' => 'backend\behaviors\TimestampStringBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['fecha_registro', 'tstamp'],
                ]
            ]

        ];

    }

    public function rules()
    {
        return
            [

                [
                    ['compania','tipo_mov'],'required'
                ],
                [
                    [
                        "compania","tipo_mov",
                        "estado","usuario","regimen_vacaciones",
                        "fecha_inicial","fecha_final","motivo_goce_vacaciones"
                        ,"codigo_puesto","fecha_registro",
                        "estado_flujo_proceso","codigo_empleado"
                        ,"tstamp"
                    ],"string"
                ],
                [
                  [
                      "consecutivo_movimiento","dias_feriados",
                      "dias_descanso","dias_calendario","periodo",
                      "codigo_nodo_organigrama"

                  ],"integer"

                ],
                [
                    ["dias_obsequiados","dias_pagados"],"double"
                ],
                [
                    ['dias_habiles'],'double','min' => 1,'on' => self::SCENARIO_INSERT,
                    'tooSmall' => \Yii::t('app/error','diasHabilesInvalidos'),
                ]
        ];
    }

    public function calcularDesgloseVacaciones()
    {
        $generalVacacionesCalculator = new GeneralVacacionesCalculator();
        $generalVacacionesCalculator->setMovimientoVacaciones($this);
        $this->setAttributes($generalVacacionesCalculator->calcularVacaciones());
        $this->getDiasHabilesExtras();
        $this->validate();

    }

    protected function getDiasHabilesExtras()
    {
        $vacacionesCalcFactory = new VacacionesCalculatorFactory($this);
        $calculator = $vacacionesCalcFactory->getVacacionesCalculator();
        if(!empty($calculator))
        {
            $this->setAttributes($calculator->calcularVacaciones());

        }
    }

    public function guardarDesgloseVacaciones()
    {
        $vacacionesEmpleadoMovimientoHelper = new VacacionesEmpleadoMovimientoHelper($this);
        $vacacionesEmpleadoMovimientoHelper->guardarVacacionesEmpleado();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getControlAjusteVacacionesMov()
    {
        return $this->hasOne(ControlAjusteVacacionesMovimiento::className(),["compania"=>"compania",
                                                                        "tipo_mov"=>"tipo_mov",
                                                                        "consecutivo_movimiento"=>"consecutivo_movimiento"]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVacacionesEmpleadoMovimiento()
    {
        return $this->hasMany(VacacionEmpleadoMovimiento::className(),["compania"=>"compania",
                                                                        "tipo_mov"=>"tipo_mov",
                                                                        "consecutivo_movimiento"=>"consecutivo_movimiento"]);
    }
    /**
     * @return ActiveQuery
     */
    public function getProcesoMovimientoVacaciones()
    {
        return $this->hasOne(ProcesoMovimientoVacacion::className(),["compania"=>"compania",
                                                                    "tipo_mov"=>"tipo_mov",
                                                                    "consecutivo_movimiento"=>"consecutivo_movimiento"]);
    }
    /**
     * @return ActiveQuery
     */
    public function getEmpleado()
    {
        return $this->hasOne(Empleado::className(),['compania'=>'compania',
                                                    'codigo_empleado'=>'codigo_empleado']);
    }

    public function setDefaultValueFromEmpleado()
    {

        $empleado = Empleado::find()->where($this->getAttributes(Empleado::primaryKey()))->one();
        if(!empty($empleado))
        {
            $this->codigo_nodo_organigrama = $empleado->codigo_nodo_organigrama;
            $this->codigo_puesto = $empleado->codigo_puesto;

        }

    }
    public function setDefaultValues()
    {
        $this->setDefaultValueFromEmpleado();
        $this->estado = 'T';
        $this->regimen_vacaciones;
        $this->enterWorkflow();
    }

    /**
     * @return mixed
     */
    public function getDiasDisponiblesVacaciones()
    {
        return $this->getVacacionesEmpleadoMovimiento()->sum('dias_disponibles');
    }

    public function extraFields()
    {
        return ['diasDisponiblesVacaciones'];
    }

    public function getSubjectProcesoDescription()
    {
        if(!empty($this->fecha_inicial) && !empty($this->fecha_final))
        {
            $dateTimeFechaInicial = new \DateTime($this->fecha_inicial);
            $fechaInicial =  $dateTimeFechaInicial->format(\Yii::$app->params['displayDateFormat']);
            $dateTimeFechaFinal = new \DateTime($this->fecha_final);
            $fechaFinal =  $dateTimeFechaFinal->format(\Yii::$app->params['displayDateFormat']);
            return "Solicitud de Vacaciones del ".$fechaInicial." hasta ".$fechaFinal;

        }

        return "";
    }

    public function getNotificationSubject()
    {
        return $this->getSubjectProcesoDescription();
    }




}