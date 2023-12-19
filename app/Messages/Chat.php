<?php
namespace SocketAPP\Messages;
use libs\Logsys\Logs;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SocketAPP\Models\AnchorModel;
use SocketAPP\Models\LiveOnlineModel;

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

//        $numRecv = count($this->clients) - 1;
//        $msgLog = sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
//            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
//        Logs::info("{$msgLog}");
        $data = json_decode($msg, true);

        if ($data['type']=='pong'){
            $dataRes = [
                'type' => 'init',
                'data' => [
                    'message' => 'success'
                ]
            ];
            $from->send(json_encode($dataRes));
        } elseif ($data['type']=='bid') {
            $anchorData = AnchorModel::where(['user_id' => $data['anchorId'], 'live_status' => 1])->orderBy('add_time', 'DESC')->first();
            $liveCount = LiveOnlineModel::where('anchor_id', $anchorData->id)->count();
            $liveCountIfUser = LiveOnlineModel::where(['anchor_id' => $anchorData->id, 'user_id' => $data['data']['id']])->count();
            if($liveCountIfUser <= 0) {
                if(isset($data['data']['id'])){
//                    Logs::info("AnchorModel {$anchorData}");
                    if($anchorData){
                        $liveData = new LiveOnlineModel;
                        $liveData->anchor_id = $anchorData->id;
                        $liveData->live_stream_address = $anchorData->live_stream_address;
                        $liveData->user_id = $data['data']['id'];
                        $liveData->add_time = time();
                        $liveData->save();
                    }
                }
            }
            $dataRes = [
                'type' => 'onlineList',
                'data' => $liveCount,
                'web' => $data['web'],
                'anchorId' => $data['anchorId']
            ];
            $from->send(json_encode($dataRes));
        } elseif ($data['type']=='msg') {
            Logs::info("message type");
            $from->send($msg);
        } elseif ($data['type']=='userLogout') {
            $anchorData = AnchorModel::where(['user_id' => $data['anchorId'], 'live_status' => 1])->orderBy('add_time', 'DESC')->first();
            LiveOnlineModel::where(['anchor_id' => $anchorData->id, 'user_id' => $data['data']['id']])->delete();
            $from->send($msg);
        } elseif ($data['type']=='giveGift') {
            Logs::info("giveGift type");
            $from->send($msg);
        } elseif ($data['type']=='endLive') {
            Logs::info("endLive type");
            $from->send($msg);
        } elseif ($data['type']=='login') {
            Logs::info("login type");
            $from->send($msg);
        } elseif ($data['type']=='kickedOut') {
            Logs::info("kickedOut type");
            $from->send($msg);
        } elseif ($data['type']=='updateSendMsg') {
            Logs::info("updateSendMsg type");
            $from->send($msg);
        }
//        foreach ($this->clients as $client) {
//            if ($from !== $client) {
//
//            }
//        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);
        $date = date('d-m-Y-H-i-s');
        $logs = "Connection {$conn->resourceId} has disconnected on {$date}\n";
        Logs::warning($logs);
        echo $logs;
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $logs = "An error has occurred: {$e->getMessage()}\n";
        $date = date('d-m-Y-H-i-s');
        Logs::error($logs.' - '.$date);
        $conn->close();
    }
}