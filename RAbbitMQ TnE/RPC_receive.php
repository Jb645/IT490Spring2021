#!/usr/bin/php
<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('25.14.14.158', 5672, 'test', 'test');
$channel = $connection->channel();

$channel->queue_declare('rpc_queue', false, false, false, false);



echo " [x] Awaiting RPC requests\n";
$callback = function ($req) {
    $output = intval($req->body);
    echo ' [.] fib(', $output, ")\n";

    $msg = new AMQPMessage(
        (string) fib($output),
        array('correlation_id' => $req->get('correlation_id'))
    );

    $req->delivery_info['channel']->basic_publish(
        $msg,
        '',
        $req->get('reply_to')
    );
    $req->ack();
};

$channel->basic_qos(null, 1, null);
$channel->basic_consume('rpc_queue', '', false, false, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}

$channel->close();
$connection->close();
?>
