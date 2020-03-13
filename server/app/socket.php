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
            $this->clients[$cid]->send('reping');
        } else if ($msg == 'demo') {
            $this->clients[$cid]->send('https://siasky.net/_B3zqoBcdUKDApSM434MAI71asJXa6i6TMjdIZhDgfGaCg');
            $this->clients[$cid]->send('https://siasky.net/_BFEVbLBDtJ7JQDBtQk7-hZpFRKI7QWXvszbVY_IdSyLTg');
            $this->clients[$cid]->send('https://siasky.net/_BlJiv1cnrEViM4aiivcn8zEawM-Y54esbtN_Ia5-jqnyA');
            $this->clients[$cid]->send('https://siasky.net/_B1waOpGBPqvBwx7IG8g62fjJIxce2co7KKLG5DTwiRVHA');
            $this->clients[$cid]->send('https://siasky.net/AACB1Uoh2uUCbaAAocUBIyqCcVhRlOVQSB4Xl1qTgjeeDA');
            $this->clients[$cid]->send('https://siasky.net/AACs2LgXSEOzq5MWizVdXWCaU5DNFyL8E730HoDGNalUbA');
            $this->clients[$cid]->send('https://siasky.net/_B3gNlY25lEgjCfOSgd5DheOqVQFJUSrIkxSPYZTBqymQw');
            $this->clients[$cid]->send('https://siasky.net/AAAc2Qpgyu9m2-2xknCYc9S2eUG2d84o8Y-zxINVJgFunQ');
            $this->clients[$cid]->send('https://siasky.net/_B0v5QQ8NOoLDJNYLrkOvGCLoLYzmzEO4_VFXJakpH-nKw');
            $this->clients[$cid]->send('https://siasky.net/_B0VJqOUeHcbMo8Jm4BIPE38wYFX2LkF6fir3QzZXyxhtg');
            $this->clients[$cid]->send('https://siasky.net/_AHjj5YX2Yp0a18D8gqGNITRGFjywzW8RPnbgOFbeoOaAg');
            $this->clients[$cid]->send('https://siasky.net/AABAwpg3tCmTJ7shRWv9UCgn1U7On40yNPUCdKm7H7D3Pg');
            $this->clients[$cid]->send('https://siasky.net/AABxsW7XVZZA0BinWAX7M_lIB3vejASe6VMxerGYrCClHw');
            $this->clients[$cid]->send('https://siasky.net/AADs2Y0uRg9NDd4k-0dfrL5DvmEcWpTqpvdugrwQNe9GVg');
            $this->clients[$cid]->send('https://siasky.net/AAC4hHFAsiH5U-ZHjADa3AXC5ziDLR5JkLNG1eScAfqI3A');
            $this->clients[$cid]->send('https://siasky.net/_B2H32TA93K0EAyfSc93u_pq0UGzB9JXRoZ7pWxXMGa9Kg');
            $this->clients[$cid]->send('https://siasky.net/_BW9wTyA6BqeGEs9h-Yo4ePM6F5Hj1UihBdYU7Q2KbTMXg');
            $this->clients[$cid]->send('https://siasky.net/AADRzR3Z__O0l6l3mgiGuuhfAuWlMJsA6Ivgo4IX1ejyuA');
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