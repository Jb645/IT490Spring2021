#!/usr/bin/php
<?php
require_once('rabbitMQLib.inc');
function amqpLoginRequest($username, $password)
{
  $client = new rabbitMQClient("testRabbitMQ.ini","testServer"); 
  if (isset($argv[1]))
  {
    $msg = $argv[1];
  }
  else
  {
    $msg = "Login request for: " . $username;
  }
  $request = array();
  $request['type'] = "Login";
  $request['username'] = $username;
  $request['password'] = $password;
  $request['message'] = $msg;
  $response = $client->send_request($request);
  //$response = $client->publish($request);
  echo "client received response: ".PHP_EOL;
  echo "\n\n";
  return $response['returnCode'];
}

function amqpLeaderboard()
{
  $client = new rabbitMQClient("testRabbitMQ.ini","testServer");
  if (isset($argv[1]))
  {
    $msg = $argv[1];
  }
  else
  {
    $msg = "Leaderboard request";
  }
  $request = array();
  $request['type'] = "get-leaderboard";;
  $response = $client->send_request($request);
  //$response = $client->publish($request);
  echo "client received response: ".PHP_EOL;
  echo "\n\n";
  return $response['returnCode'];

}

function amqpWeather($location)
{
  $client = new rabbitMQClient("testRabbitMQ.ini","testServer");
  if (isset($argv[1]))
  {
    $msg = $argv[1];
  }
  else
  {
    $msg = "Requesting weather info for: " . $location;
  }
  $request = array();
  $request['type'] = "weather";
  $request['location'] = $location;
  $request['message'] = $msg;
  $response = $client->send_request($request);
  echo "client received response: ".PHP_EOL;
  echo "\n\n";
  return $response;
}

/*$USER = $_GET['username']; 
$PASS = $_GET['password'];
amqpLoginRequest($USER, $PASS);
*/
