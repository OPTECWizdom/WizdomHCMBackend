<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 22/01/2018
 * Time: 14:56
 */

namespace backend\pushNotifications;


use yii\base\Component;

class PushNotification extends Component
{
    /**
     * @var string $task
     */
    private $task;
    /**
     * @var string $action
     */
    private $action;
    /**
     * @var string $message
     */

    private $message;
    /**
     * @var string $destiny;
     */
    private $destiny;



    public function sendPushNotification()
    {
        $json = json_encode($this);
        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'wizdom_hcm_pusher');
        try {
            $socket->connect("tcp://localhost:5555");
            $socket->send($json);
        }catch (\Exception $e)
        {

        }

    }

    /**
     * @return string
     */
    public function getTask(): string
    {
        return $this->task;
    }

    /**
     * @param string $task
     */
    public function setTask(string $task)
    {
        $this->task = $task;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $action
     */
    public function setAction(string $action)
    {
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getDestiny(): string
    {
        return $this->destiny;
    }

    /**
     * @param string $destiny
     */
    public function setDestiny(string $destiny)
    {
        $this->destiny = $destiny;
    }







}