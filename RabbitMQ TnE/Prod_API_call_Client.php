#!/usr/bin/php
<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('25.14.14.158', 5672, 'test', 'test',"QA&Prod");
$channel = $connection->channel();

$channel->queue_declare('Weather', false, false, false, false);

function returnCall($n){
  echo ' [x] Received ',$n, "\n";

  $output = "IP received".$n;
  shell_exec("printf '$n' >> ip.txt");
  return $output;

}

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function($req){
  $n = $req->body;
  echo 'this is the return',$n;

  $msg = new AMQPMessage((string) returnCall($n),array('correlation_id'=>$req->get('correlation_id')));

  $req->delivery_info['channel']->basic_publish($msg,'',$req->get('reply_to'));
  $req->ack();

};

/*
$callback = function ($msg) {
    echo ' [x] Received ', $msg->body, "\n";
    sleep(substr_count($msg->body, '.'));
    $output = shell_exec("python3 WeatherPONG.py ".$msg->body);
    var_dump($output);
    echo " [x] Done\n";
    $msg->ack();
};
*/
$channel->basic_qos(null, 1, null);
$channel->basic_consume('Weather', '', false, false, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}

$channel->close();
$connection->close();
?>
