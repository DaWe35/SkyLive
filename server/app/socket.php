<?php
namespace MyApp;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Socket implements MessageComponentInterface {
    public function __construct() {
        $this->clients = [];
        $this->connectionCount = 0;
        $this->last = '';
        $this->last_1 = '';
        echo "Socket started\n";
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection in $this->clients
        $cid = $conn->resourceId;
        $this->clients[$cid] = $conn;
        $this->connectionCount += 1;
        echo "{$this->connectionCount} peers, new connection: {$conn->resourceId}\n";
        $this->clients[$cid]->send( $this->last_1 );
        $this->clients[$cid]->send( $this->last );
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $cid = $from->resourceId;
        if ($msg == 'ping') {
            $this->clients[$cid]->send( 'reping' );
        } else {
            $msg = json_decode($msg, true);
            if ($msg['password'] == socketPassword) {
                $this->last_1 = $this->last;
                $this->last = $msg['data'];
                echo $msg['data'] . "\n";
                foreach ( $this->clients as $client ) {
                    $client->send( $msg['data'] );
                }
            } else {
                $this->clients[$cid]->send( 'wrong_pass' );
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->connectionCount -= 1;
        echo "{$this->connectionCount} peers\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
    }
}