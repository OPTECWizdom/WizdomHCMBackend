<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 22/08/2017
 * Time: 17:16
 */

namespace backend\models\proceso;


use backend\models\proceso\flujoProceso\flujoProcesoAgente\FlujoProcesoAgente;
use backend\models\procesoModelConnector\FactoryProcesoSubjectConnector;
use backend\utils\agenteSearcher\IAgenteSearchable;
use yii\db\ActiveRecord;
use backend\models\empleado\Empleado;

/**
 * Class Proceso
 * @package backend\models\proceso
 *
 */
class Proceso extends ActiveRecord implements IAgenteSearchable
{
    /**
     * @var ActiveRecord $modelProceso
     */
    public $modelProceso;

    public function init(){
        $this->on($this::EVENT_BEFORE_DELETE,[$this,"deleteFlujoProcesoAgente"]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */

    public function getEmpleadoSolicitante()
    {
        return $this->hasOne(Empleado::className(),["compania"=>"compania","codigo_empleado"=>"codigo_empleado"]);
    }


    public static function tableName()
    {
        return 'PROCESO';
    }

    public static function primaryKey()
    {
        return ['compania','tipo_flujo_proceso','id_proceso'];
    }



    public function rules()
    {
        return
                [
                    [
                        [
                            "compania","tipo_flujo_proceso",
                            "codigo_empleado","sistema_procedencia"
                        ],
                        "required","on"=>['register']
                    ],
                    [
                      [
                          "compania","tipo_flujo_proceso",
                          "codigo_empleado","sistema_procedencia"
                      ],"string"
                    ],
                    [
                        ["id_proceso"],"integer"
                    ],
                    [
                        ["fecha_creacion","tstamp"],"string"
                    ]

                ];

    }
    public function behaviors()
    {
        return
            [
                'timestamp' => [
                    'class' => 'backend\behaviors\TimestampStringBehavior',
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['fecha_creacion', 'tstamp'],
                    ],
                ],
                [

                    'class' => 'common\components\mdmsoft\autonumber\Behavior',
                    'attribute' => 'id_proceso', // required

                ]
        ];
    }

    public function getEmpleadoPrincipal()
    {
        return $this->getEmpleadoSolicitante()->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */

    public function getFlujoProcesoAgente()
    {
        return $this->hasMany(FlujoProcesoAgente::className(),["compania"=>"compania",
                                                              "tipo_flujo_proceso"=>"tipo_flujo_proceso",
                                                              "id_proceso"=>"id_proceso"]);
    }


    public function deleteFlujoProcesoAgente()
    {
        $flujoProcesoAgentes = $this->getFlujoProcesoAgente()->all();
        foreach ($flujoProcesoAgentes as $flujoProcesoAgente)
        {
            $flujoProcesoAgente->delete();
        }
    }

    public function getProcesoSubject()
    {
        $factory = new FactoryProcesoSubjectConnector();
        $connector = $factory->getSubjectProceso($this);
        if (!empty($connector)) {
            $subject = $connector->getProcesoSubject();
            return $subject;

        }
        return null;
    }







}