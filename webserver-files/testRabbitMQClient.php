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
  $request['type'] = "login";
  $request['username'] = $username;
  $request['password'] = $password;
  $request['message'] = $msg;
  $response = $client->send_request($request);
  //$response = $client->publish($request);
  echo "client received response: ".PHP_EOL;
  echo "\n\n";
  return $response;
}

function amqpCreateAccount($username, $password)
{
  $client = new rabbitMQClient("testRabbitMQ.ini","testServer"); 
  if (isset($argv[1]))
  {
    $msg = $argv[1];
  }
  else
  {
    $msg = "Creating account for " . $username;
  }
  $request = array();
  $request['type'] = "create_account";
  $request['username'] = $username;
  $request['password'] = $password;
  $request['message'] = $msg;
  $response = $client->send_request($request);
  //$response = $client->publish($request);
  echo "client received response: ".PHP_EOL;
  echo "\n\n";
  return $response;
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

function amqpGameResults($winner, $loser, $winscore, $losescore, $weather)
{
  $client = new rabbitMQClient("testRabbitMQ.ini","testServer");
  if (isset($argv[1]))
  {
    $msg = $argv[1];
  }
  else
  {
    $msg = "Results for game";
  }
  $request = array();
  $request['type'] = "results";
  $request['winner'] = $winner;
  $request['loser'] = $loser;
  $request['wscore'] = $winscore;
  $request['lscore'] = $losescore;
  $request['weather'] = $weather;
  $request['message'] = $msg;
  $response = $client->send_request($request);
  echo "client received response: ".PHP_EOL;
  echo "\n\n";
  return $response;
}

function amqpFriendslist($username)
{
  $client = new rabbitMQClient("testRabbitMQ.ini","testServer");
  if (isset($argv[1]))
  {
    $msg = $argv[1];
  }
  else
  {
    $msg = "Get friends list";
  }
  $request = array();
  $request['type'] = "friendslist";
  $request['username'] = $username;
  $request['message'] = $msg;
  $response = $client->send_request($request);
  echo "client received response: ".PHP_EOL;
  echo "\n\n";
  return $response;
}

function amqpAddFriend($username, $target)
{
  $client = new rabbitMQClient("testRabbitMQ.ini","testServer");
  if (isset($argv[1]))
  {
    $msg = $argv[1];
  }
  else
  {
    $msg = "Add friend";
  }
  $request = array();
  $request['type'] = "addfriend";
  $request['username'] = $username;
  $request['target'] = $target;
  $request['message'] = $msg;
  $response = $client->send_request($request);
  echo "client received response: ".PHP_EOL;
  echo "\n\n";
  return $response;
}

function amqpRmFriend($username, $target)
{
  $client = new rabbitMQClient("testRabbitMQ.ini","testServer");
  if (isset($argv[1]))
  {
    $msg = $argv[1];
  }
  else
  {
    $msg = "Remove friend";
  }
  $request = array();
  $request['type'] = "rmfriend";
  $request['username'] = $username;
  $request['target'] = $target;
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
