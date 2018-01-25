<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 22/01/2018
 * Time: 14:56
 */

namespace backend\pushNotifications;


use yii\base\Model;

class PushNotification extends Model
{
    /**
     * @var string $task
     */
    public $task;
    /**
     * @var string $action
     */
    public $action;
    /**
     * @var string $message
     */

    public $message;
    /**
     * @var string[] $destinies;
     */
    public $destinies;



    public function sendPushNotification()
    {
        \Yii::info("Enviando Notificacion","Push");
        foreach ($this->destinies as $destiny)
        {
            $json = $this->getAttributes(["action","task","message"]);
            try{
                $context = new \ZMQContext();
                $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'wizdom_hcm_pusher');
                $socket->connect("tcp://localhost:5555");
                $json["destiny"] = $destiny;
                \Yii::info("Push Notification ".json_encode($json));
                $socket->send(json_encode($json));

            }
            catch(\Exception $e){
                \Yii::error('Push Notification : Failed to send notification'.json_encode($json));

            }



        }


    }



    public function rules()
    {
        return
            [
                [
                    [
                        "action","task",
                        "destinies","message"
                    ],
                    "string"
                ]
            ];

    }







}