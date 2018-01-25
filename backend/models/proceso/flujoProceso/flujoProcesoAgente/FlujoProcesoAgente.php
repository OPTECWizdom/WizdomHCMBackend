<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 25/08/2017
 * Time: 12:36
 */

namespace backend\models\proceso\flujoProceso\flujoProcesoAgente;


use backend\models\proceso\flujoTipoProceso\FlujoTipoProceso;
use backend\pushNotifications\activeRecord\AbstractNotificationPusherObject;
use backend\pushNotifications\activeRecord\DeleteNotificationPusher;
use backend\pushNotifications\activeRecord\InsertNotificationPusher;
use yii\db\ActiveRecord;
use backend\models\proceso\flujoProceso\FlujoProceso;
use backend\models\proceso\Proceso;
use backend\models\empleado\Empleado;
use backend\models\proceso\flujoTipoProceso\flujoTipoProcesoCorreoExterno\FlujoTipoProcesoCorreoExterno;
use backend\utils\agenteSearcher\AgenteSearcherFactory;
use backend\utils\email\IEmailable;
class FlujoProcesoAgente extends AbstractNotificationPusherObject implements IEmailable
{


    public static function tableName()
    {
        return "FLUJO_PROCESO_AGENTE";
    }

    public static function primaryKey()
    {
        return [
            "compania", "id_proceso", "tipo_flujo_proceso", "codigo_tarea", "consecutivo"
        ];
    }


    public function rules()
    {
        return [
            [
                ["compania", "id_proceso", "tipo_flujo_proceso", "codigo_tarea", "consecutivo"], "required"

            ],
            [
                ["agente", "parametro_agente", "tstamp", "correo_enviado", "fecha_creacion"],
                "string"
            ],
            [
                ["tiempo_espera"],
                "integer"
            ]


        ];
    }


    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'backend\behaviors\TimestampStringBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['fecha_creacion', 'tstamp'],
                ]
            ],

        ];

    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFlujoProceso()
    {
        return $this->hasOne(FlujoProceso::className(), ["compania" => "compania", "id_proceso" => "id_proceso",
            "tipo_flujo_proceso" => "tipo_flujo_proceso",
            "codigo_tarea" => "codigo_tarea"]);
    }

    public function getProceso()
    {
        return $this->hasOne(Proceso::className(), ["compania" => "compania", "tipo_flujo_proceso" => "tipo_flujo_proceso",
            "id_proceso" => "id_proceso"]);
    }

    public function getFlujoTipoProcesoCorreoExterno()
    {
        return $this->hasOne(FlujoTipoProcesoCorreoExterno::className(), ["compania" => "compania", "tipo_flujo_proceso" => "tipo_flujo_proceso",
            "codigo_tarea" => "codigo_tarea"]);
    }

    /**
     * IEmailable Methods
     */

    public static function findPendingEmails()
    {
        return self::find()
            ->joinWith('flujoProceso')->where(['{{flujo_proceso_agente}}.correo_enviado' => 'N',
                '{{flujo_proceso}}.estado' => 'FlujoProcesoWorkflow/PE'])
            ->with('proceso')
            ->with('flujoTipoProcesoCorreoExterno')
            ->all();
    }


    public function setSentStatus()
    {
        $this->setAttribute('correo_enviado', 'S');

    }


    public function getSubjectEmail()
    {
        /**
         * @var Empleado $empleado
         */
        $empleado = $this->proceso->getEmpleadoSolicitante()->one();
        $asunto = $this->flujoTipoProcesoCorreoExterno->getAttribute('asunto');
        if (!empty($empleado)) {
            $asunto .= " - " . ucwords(strtolower($empleado->getNombreCompleto()));
        }
        return $asunto;
    }


    public function getHTMLBody()
    {
        return $this->flujoTipoProcesoCorreoExterno->mensaje;

    }


    public function getEmailBody()
    {
        return '';
    }


    public function getDestinations()
    {

        $agenteSearcherFactory = new AgenteSearcherFactory($this->proceso, $this->parametro_agente);
        $agenteSearcher = $agenteSearcherFactory->createAgenteSearcher($this->agente);
        $empleados = $agenteSearcher->search();
        if (!empty($empleados)) {
            $empleados = array_map(function ($empleado) {
                return $empleado->getAttribute('correo_electronico_principal');
            },
                $empleados);
        }
        return $empleados;

    }

    public function getEmpleadosEjecutantes()
    {
        $agenteSearcherFactory = new AgenteSearcherFactory($this->proceso, $this->parametro_agente);
        $agenteSearcher = $agenteSearcherFactory->createAgenteSearcher($this->agente);
        $empleados = $agenteSearcher->search();
        return $empleados;

    }

    public function getFlujoTipoProceso()
    {
        return $this->hasOne(FlujoTipoProceso::className(),["compania"=>"compania","tipo_flujo_proceso"=>"tipo_flujo_proceso",
                                                            "codigo_tarea"=>"codigo_tarea"]);
    }


    public function getHTMLBodyParms()
    {
        return ['proceso' => $this->proceso];

    }

    public function isEmail()
    {
        return !empty($this->flujoTipoProcesoCorreoExterno);
    }

    public function getPushNotificationDestinies()
    {
        $empleados = $this->getEmpleadosEjecutantes();
        $codigosEmpleados = [];
        foreach ($empleados as $empleado)
        {
            $codigoEmpleado = $empleado->getAttribute('codigo_empleado');
            if(!in_array($codigoEmpleado,$codigosEmpleados))
            {
                $codigosEmpleados[] = $codigoEmpleado;
            }
        }
        return $codigosEmpleados;
    }

    public  function getCreatedPushNotificationMessage()
    {
        $message = \Yii::t('app','nuevaTarea');
        $message.= " : ".$this->getFlujoTipoProceso()->one()->getAttribute("descripcion_tarea");
        return $message;

    }

    public  function getUpdatedPushNotificationMessage()
    {
        $description = $this->getFlujoTipoProceso()->one()->getAttribute("descripcion_tarea");
        $message = \Yii::t('app','tareaEliminada',["tarea"=>$description]);
        return $message;


    }

    public  function getDeletedPushNotificationMessage()
    {
        return "";
    }

    public  function getPushNotificationDefaultMessage()
    {
        return "";
    }

    public  function getPushNotificationTask()
    {

        return "flujoProceso";
    }

    public function attachNotificationsPusher()
    {
        $insert = new InsertNotificationPusher();
        $insert->attachEvents($this);
        $delete = new DeleteNotificationPusher();
        $delete->attachEvents($this);
    }

    public  function getPushNotificationTitle()
    {
        return  \Yii::t('app','tareas');

    }


}