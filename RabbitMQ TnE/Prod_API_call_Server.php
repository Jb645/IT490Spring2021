#!/usr/bin/php
<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Weather{

private $connection;
private $channel;
private $callback_queue;
private $response;
private $corr_id;

  public function __construct(){
    //connection string
    $this->connection = new AMQPStreamConnection('25.14.14.158', 5672, 'test', 'test',"QA&Prod");

  //make a connection to the channel on the server
  $this->channel = $this->connection->channel();

  //This is the queue, not sure why the queue section is blank, get every Q i think but only answers to the one at the bottom
  list($this->callback_queue, ,) = $this->channel->queue_declare('', false, true, false, false);

  //I think this is how to get the response from consumer
  $this->channel->basic_consume($this->callback_queue,'',false,true,false,false,array($this,'onResponse'));

}

  public function onResponse($rep){

    if ($rep->get('correlation_id')==$this->corr_id){
        $this->response = $rep->body;
    }
  }




  public function call($n){
    $this->response = null;
    $this->corr_id = uniqid();

  $msg = new AMQPMessage((string) $n,array('correlation_id'=>$this->corr_id,'reply_to'=>$this->callback_queue));

  $this->channel->basic_publish($msg,'','Weather');

  while(!$this->response){
    $this->channel->wait();
  }
  return $this->response;


  }

}
//Connection string
//$connection = new AMQPStreamConnection('25.14.14.158', 5672, 'test', 'test');

//connects to the channel
//$channel = $connection->channel();

//chooses the queue and the type of message its gonna send
//$channel->queue_declare('task_queue', false, true, false, false);


if (empty($data)) {
  $data = shell_exec("./info.sh
  ");
}
/*
$msg = new AMQPMessage(
    $data,
    array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT)
);

$channel->basic_publish($msg, '', 'task_queue');

echo ' [x] Sent ', $data, "\n";
*/





$API_call = new Weather();
$response = $API_call->call($data);

echo'[x]',$response, "\n";


//Don't close connection to be able to get message back
//$channel->close();
//$connection->close();
?>
