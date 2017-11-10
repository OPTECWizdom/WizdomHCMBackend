<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 22/08/2017
 * Time: 9:42
 */

namespace backend\models;


use backend\commands\BackendBackgroundProcessFactory;
use raoul2000\workflow\events\WorkflowEvent;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Exception;

class MovimientoVacaciones extends  ActiveRecord
{




    public function init()
    {
        parent::init();
        $this->on(self::EVENT_BEFORE_INSERT,[$this,'calcularDesgloseVacaciones']);
        $this->on(self::EVENT_BEFORE_INSERT,[$this,'setDefaultValues']);
        $this->on(self::EVENT_AFTER_INSERT,[$this,'guardarDesgloseVacaciones']);

    }

    public static function tableName()
    {
        return 'movimientos_vacaciones';
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

                'class' => 'mdm\autonumber\Behavior',
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
                    ['compania','tipo_mov','consecutivo_movimiento'],'required'
                ],

                [
                    [
                        "compania","tipo_mov","consecutivo_movimiento",
                        "estado","usuario","regimen_vacaciones",
                        "fecha_inicial","fecha_final","motivo_goce_vacaciones",
                        "codigo_nodo_organigrama","codigo_puesto","fecha_registro",
                        "estado_flujo_proceso","codigo_empleado"
                        ,"tstamp"
                    ],"string"
                ],
                [
                  [
                    "consecutivo_movimiento","dias_feriados",
                    "dias_descanso","dias_calendario","periodo"

                  ],"integer"

                ],
                [
                    ["dias_habiles", "dias_obsequiados","dias_pagados"],"double"
                ],



        ];
    }



    public function calcularDesgloseVacaciones()
    {
        $generalVacacionesCalculator = new GeneralVacacionesCalculator();
        $generalVacacionesCalculator->setMovimientoVacaciones($this);
        $this->setAttributes($generalVacacionesCalculator->calcularVacaciones());
        $this->getDiasHabilesExtras();

    }


    protected function getDiasHabilesExtras()
    {
        $cincoMasUnoCalc = new CincoMasUnoVacacionesCalculator();
        $cincoMasUnoCalc->setMovimientoVacaciones($this);
        $this->setAttributes($cincoMasUnoCalc->calcularVacaciones());
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

















}