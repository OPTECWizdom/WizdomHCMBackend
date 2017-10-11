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
                        "estado_flujo_proceso"
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
                    ["dias_habiles"],"double"
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

/*
    public function afterEnterStatusAI()
    {
        $pks = $this->getAttributes(["compania","codigo_empleado"]);
        $diasHabiles = $this->getAttribute("dias_habiles");
        $vacacionesEmpleado = VacacionesEmpleado::find()->select(["dias_disponibles"])->where($pks)->sum('dias_disponibles');
        $saldoDisponible = 0;
        if(!empty($vacacionesEmpleado))
        {
            $saldoDisponible = $vacacionesEmpleado - $diasHabiles;
        }
        if($saldoDisponible<=0)
        {
            $this->sendToStatus('AS');
        }
        else
        {
            $this->sendToStatus('AP');

        }

    }
*/




}