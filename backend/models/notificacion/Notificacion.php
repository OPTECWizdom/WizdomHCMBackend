<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 31/08/2017
 * Time: 10:28
 */

namespace backend\models\notificacion;


use backend\pushNotifications\activeRecord\AbstractNotificationPusherObject;
use backend\pushNotifications\activeRecord\InsertNotificationPusher;
use yii\db\ActiveRecord;
use backend\utils\email\IEmailable;
use backend\models\empleado\Empleado;
class Notificacion extends AbstractNotificationPusherObject implements IEmailable
{

    public static function tableName()
    {
        return "NOTIFICACIONES";
    }

    public static function primaryKey()
    {
        return ["compania","consecutivo"];
    }
    public function behaviors()
    {
        return [
            [

                'class' => 'backend\behaviors\CustomAutoNumber',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['consecutivo'],
                ],
                "model"=>Notificacion::tableName(),
                "column" => "consecutivo"


            ],
            'timestamp' => [
                'class' => 'backend\behaviors\TimestampStringBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['fecha', 'tstamp'],
                ]
            ]

        ];

    }

    public function rules()
    {

        return [
            [
                [
                    "compania"
                ],"required"

            ],
            [
                [
                    "empleado_envia",
                    "empleado_destino","sistema_procedencia","fecha","asunto",
                    "tstamp","mensaje","naturaleza_notificacion","correo_enviado"
                ],"string"
            ],
            [
                [
                    "leido","consecutivo"
                ],"integer"
            ]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */

    public  function getEmpleadoDestino()
    {
        return $this->hasOne(Empleado::className(),["compania"=>"compania","codigo_empleado"=>"empleado_destino"]);
    }


    /**
     * IEmailable Methods
     */

    public static function findPendingEmails()
    {
        return self::find()->where(['correo_enviado'=>'N'])->all();
    }


    public function setSentStatus()
    {
        $this->setAttribute('correo_enviado','S');

    }


    public function getSubjectEmail()
    {
      return $this->asunto;
    }


    public function getHTMLBody()
    {
        return 'notificacionGenerica-html';

    }


    public function getEmailBody()
    {
        return '';
    }


    public  function getDestinations()
    {

       return $this->getEmpleadoDestino()->one()->correo_electronico_principal;

    }


    public function getHTMLBodyParms()
    {
        return ['notificacion'=>$this];

    }

    public function isEmail()
    {
        return true;
    }
    public  function getPushNotificationDestinies()
    {
        return [$this->empleado_destino];
    }

    public  function getCreatedPushNotificationMessage()
    {
        return $this->getPushNotificationDefaultMessage();
    }

    public  function getUpdatedPushNotificationMessage()
    {
        return $this->getPushNotificationDefaultMessage();

    }

    public  function getDeletedPushNotificationMessage()
    {
        return $this->getPushNotificationDefaultMessage();

    }

    public function getPushNotificationDefaultMessage()
    {
        return $this->mensaje;
    }


    public  function getPushNotificationTask()
    {
        return "notificacion";
    }

    public function attachNotificationsPusher()
    {
        $insert = new InsertNotificationPusher();
        $insert->attachEvents($this);

    }

    public function getPushNotificationTitle()
    {
        return \Yii::t('app','notificacion');
    }


}