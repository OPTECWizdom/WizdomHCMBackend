<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 22/01/2018
 * Time: 9:41
 */

namespace backend\webSocketServer;


use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;

class Pusher implements WampServerInterface {
    protected $clients;

    public function onSubscribe(ConnectionInterface $conn, $userId) {
        $this->clients[$userId->getId()] = $userId;
        echo "$userId";
    }
    public function onUnSubscribe(ConnectionInterface $conn, $userId) {
    }
    public function onOpen(ConnectionInterface $conn) {
    }
    public function onClose(ConnectionInterface $conn) {
    }
    public function onCall(ConnectionInterface $conn, $id, $userId, array $params) {
        // In this application if clients send data it's because the user hacked around in console
        $conn->close();
    }
    public function onPublish(ConnectionInterface $conn, $userId, $event, array $exclude, array $eligible) {
        // In this application if clients send data it's because the user hacked around in console
        $conn->close();
    }
    public function onNotificationEntry($notification)
    {
        echo "$notification";
        $notification = json_decode($notification,true);
        // If the lookup topic object isn't set there is no one to publish to
        if (!array_key_exists($notification['destiny'], $this->clients)) {
            return;
        }

        $users = $this->clients[$notification['destiny']];

        // re-send the data to all the clients subscribed to that category
        $users->broadcast($notification);

    }
    public function onError(ConnectionInterface $conn, \Exception $e) {
    }

}