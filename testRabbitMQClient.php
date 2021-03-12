#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$client = new rabbitMQClient("testRabbitMQ.ini","testServer");
if (isset($argv[1]))
{
  $msg = $argv[1];
}
else
{
  $msg = "test message";
}

$request = array();
  $request['type'] = "results";
  $request['winner'] = "timc";
  $request['loser'] = "test10";
  $request['wscore'] = "7";
  $request['lscore'] = "";
 // $request['wweather'] = $wweather;
 // $request['lweather'] = $lweather;
$request['message'] = $msg;
$response = $client->send_request($request);
//$response = $client->publish($request);

echo "client received response: ".PHP_EOL;
print_r($response);
echo "\n\n";

echo $argv[0]." END".PHP_EOL;

