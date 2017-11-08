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
        $this->on(self::EVENT_BEFORE_INSERT,[$this,'getDiasHabiles']);
        $this->on(self::EVENT_AFTER_INSERT,[$this,'guardarDesgloseVacaciones']);
        $this->on(self::EVENT_AFTER_UPDATE,[$this,'ejecutarVacaciones']);

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
                [
                    [
                        "compania","tipo_mov","codigo_empleado"
                    ],  'required',"on"=>['register'],
                ],
                [
                    [
                        "compania","tipo_mov","consecutivo_movimiento"
                    ],
                    'required','on'=>['update']
                ]

        ];
    }


    public function getDiasHabiles()
    {
        $calculatorVacacionesFactory = new VacacionesCalculatorFactory($this);
        $calculatorVacaciones = $calculatorVacacionesFactory->getVacacionesCalculator();
        if(!empty($calculatorVacaciones))
        {
            $diasHabiles = $calculatorVacaciones->calcularVacaciones();
            $this->setAttribute('dias_habiles',$diasHabiles);
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



    public function ejecutarVacaciones()
    {
        if($this->getAttribute('estado')=='P')
        {
           $factory =  new BackendBackgroundProcessFactory();
           $backgroundProcess = $factory->getBackgroundProcess('MovimientoVacacionesWebService');
           if(!empty($backgroundProcess))
           {
               $backgroundProcess->runJob([$this]);

           }
           else
           {
               throw new \Exception();
           }
        }
    }








}