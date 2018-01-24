<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 21/08/2017
 * Time: 12:13
 */

namespace backend\models\proceso\flujoProceso;
use yii\db\ActiveRecord;
use backend\models\proceso\flujoProceso\flujoProcesoAgente\FlujoProcesoAgenteHelper;
use backend\models\proceso\flujoTipoProceso\FlujoTipoProceso;
use backend\models\empleado\Empleado;


class FlujoProceso extends ActiveRecord
{
    public static function tableName()
    {
        return 'FLUJO_PROCESO';
    }

    public static function primaryKey()
    {
        return ['compania','id_proceso','tipo_flujo_proceso','codigo_tarea'];
    }

    public function behaviors()
    {
        return [
            [
                'class' => '\raoul2000\workflow\base\SimpleWorkflowBehavior',
                'statusAttribute' => 'estado'

            ],
            'timestamp' => [
                'class' => 'backend\behaviors\TimestampStringBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['fecha_creacion', 'tstamp'],
                ]
            ],

        ];

    }


    public function rules()
    {
        return[
            [
                [
                    "compania",'id_proceso','tipo_flujo_proceso','codigo_tarea'
                ],
                "required"
            ],
            [
                [
                    'id_proceso'
                ],"integer"

            ],
            [
                [
                    "compania",'tipo_flujo_proceso','codigo_tarea',
                    "fecha_creacion","tstamp","parametros_aplicacion","estado","codigo_empleado_ejecutante",
                    "fecha_ejecucion"
                ],
                "string"
            ]

        ];
    }
    public function init()
    {

        $this->on(
            'afterEnterStatus{FlujoProcesoWorkflow/AP}',
            [$this,'afterEnterStatusAP']
        );
    }


    public function afterInsertOperations(){
        $flujoProcesoAgenteHelper = new FlujoProcesoAgenteHelper($this);
        $flujoProcesoAgenteHelper->insertAgente();

    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert,$changedAttributes);
        if($insert){
            $this->afterInsertOperations();
        }
        if(array_key_exists('estado',$changedAttributes)){
            $this->sendNotificaciones();
        }
    }


    private function sendNotificaciones(){
        $notificacionesHelper = new FlujoProcesoNotificacionesHelper($this);
        $notificacionesHelper->insertNotificaciones();
    }

    public function afterEnterStatusAP(){
        $flujoProcesoSiguiente = $this->getNextFlujoProceso();
        if(!empty($flujoProcesoSiguiente)) {
            $flujoProcesoSiguiente->enterWorkflow();
            $flujoProcesoSiguiente->save();
        }

    }

    public function getNextFlujoProceso(){
        $nextFlujoProceso = new FlujoProceso();
        $nextFlujoProceso->setAttributes($this->getInitialAttributes());
        $flujoTipoProcesoSiguiente = FlujoTipoProceso::getNextFlujoTipoProceso($this);
        if(!empty($flujoTipoProcesoSiguiente)){
           $nextFlujoProceso->setAttribute('codigo_tarea',$flujoTipoProcesoSiguiente->getAttribute('codigo_tarea'));
           $nextFlujoProceso->fixParametrosAplicacion();
           return $nextFlujoProceso;

        }
        return null;

    }


    private function fixParametrosAplicacion(){
        $pattern = "/gs_codigo_tarea_flujo_proceso='\w+'/";
        $parametros = preg_replace($pattern,"gs_codigo_tarea_flujo_proceso='".$this->getAttribute('codigo_tarea')."'",
                    $this->getAttribute('parametros_aplicacion'));
        $this->setAttribute('parametros_aplicacion',$parametros);


    }
    private function getInitialAttributes()
    {
        return $this->getAttributes(["compania","tipo_flujo_proceso","codigo_tarea","id_proceso","parametros_aplicacion"]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */

    public function getEmpleadoEjecutante()
    {
        return $this->hasOne(Empleado::className(),["compania"=>"compania","codigo_empleado"=>"codigo_empleado_ejecutante"]);
    }



}