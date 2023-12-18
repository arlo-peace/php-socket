<?php
namespace SocketAPP\Messages;
use libs\Logsys\Logs;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        Logs::info("Connection open! ({$conn->resourceId})");
        echo "Connection open! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        $msgLog = sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
        Logs::info("{$msgLog}");
//        Logs::info("FFF" . print_r($from));

        $msgLog = sprintf('Connection %s', count($this->clients));
        Logs::info("{$msgLog}");

        $data = json_decode($msg, true);
//        $msgLog = sprintf('Connection "%s"' . "\n", $data);
//        Logs::info("{$msgLog}");
//        echo $msgLog;
        if ($data['type']=='pong'){
            $dataRes = [
                'type' => 'init',
                'data' => [
                    'message' => 'success'
                ]
            ];
            $from->send(json_encode($dataRes));
        } elseif ($data['type']=='bid') {
            $dataRes = [
                'type' => 'onlineList',
                'data' => count($this->clients),
                'web' => $data['web'],
                'anchorId' => $data['anchorId']
            ];
            $from->send(json_encode($dataRes));
        } elseif ($data['type']=='msg') {
            $from->send($msg);
        } elseif ($data['type']=='userLogout') {
            $from->send($msg);
        } elseif ($data['type']=='giveGift') {
            $from->send($msg);
        } elseif ($data['type']=='endLive') {
            $from->send($msg);
        } elseif ($data['type']=='login') {
            $from->send($msg);
        } elseif ($data['type']=='kickedOut') {
            $from->send($msg);
        } elseif ($data['type']=='updateSendMsg') {
            $from->send($msg);
        }
//        foreach ($this->clients as $client) {
//            if ($from !== $client) {
//                // The sender is not the receiver, send to each client connected
//                Logs::info("Send Message");
////                $client->send($msg);
//            }
//        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);
//        $date = date('d-m-Y-H-i-s');
//        $logs = "Connection {$conn->resourceId} has disconnected on {$date}\n";
//        Logs::warning($logs);
//        echo $logs;
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $logs = "An error has occurred: {$e->getMessage()}\n";
        $date = date('d-m-Y-H-i-s');
        Logs::error($logs.' - '.$date);
        $conn->close();
    }
}