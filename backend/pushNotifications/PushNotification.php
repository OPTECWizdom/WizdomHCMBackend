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
        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'wizdom_hcm_pusher');
        $socket->connect("tcp://localhost:5555");
        foreach ($this->destinies as $destiny)
        {
            $json = $this->getAttributes([],["destinies"]);
            $json["destiny"] = $destiny;
            $socket->send(json_encode($json));


        }
       /* try {
            $socket->connect("tcp://localhost:5555");
            $socket->send($json);
      *//*  }catch (\Exception $e)
        {

        }*/

    }



    public function rules()
    {
        return
            [
                [
                    [
                        "action","task",
                        "destiny","message"
                    ],
                    "string"
                ]
            ];

    }







}