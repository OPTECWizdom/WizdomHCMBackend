<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 22/08/2017
 * Time: 9:42
 */

namespace app\models;


use raoul2000\workflow\events\WorkflowEvent;
use yii\db\ActiveRecord;

class MovimientoVacaciones extends  ActiveRecord
{


    public function init()
    {


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

            ]

        ];

    }



    public function rules()
    {
        return
            [


                [

                    [
                        "compania","tipo_mov","codigo_empleado","consecutivo_movimiento",
                        /*"fecha_inicial","fecha_final",
                        "dias_calendario","dias_habiles","dias_feriados",
                        "dias_descanso","dias_obsequiados",
                        "dias_pagados","codigo_nodo_organigrama",
                        "codigo_puesto","usuario","fecha_registro",
                        "regimen_vacaciones","periodo",
                        "modulo_origen","tstamp","estado",*/"estado_flujo_proceso"
                    ],  'required',"on"=>['update']
                ],
                [
                  ["consecutivo_movimiento"],"integer"

                ],
                [
                    [
                        "compania","tipo_mov","codigo_empleado",/*
                        "fecha_inicial","fecha_final",*/
                        /* "dias_calendario","dias_habiles","dias_feriados",*/
                        /* "dias_descanso","dias_obsequiados",
                        "dias_pagados","codigo_nodo_organigrama",
                        "codigo_puesto","usuario","fecha_registro",
                        "regimen_vacaciones","periodo",
                        "modulo_origen","tstamp",*/"estado"
                    ],  'required',"on"=>['register'],
                ],
                [
                    [
                        "compania","tipo_mov","consecutivo_movimiento",
                        "estado"
                    ],
                    'required','on'=>['update']
                ]

        ];
    }

}