<?php
require_once('rabbitMQLib.inc');
require_once('logger.php');
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
  $request['type'] = "get-leaderboard";
  $response = $client->send_request($request);
  //$response = $client->publish($request);
  echo "client received response: ".PHP_EOL;
  echo "\n\n";
  return $response;

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

function amqpWeatherHistory($location, $date)
{
  //Date format is year-mm-dd
  $client = new rabbitMQClient("testRabbitMQ.ini","testServer");
  if (isset($argv[1]))
  {
    $msg = $argv[1];
  }
  else
  {
    $msg = "Requesting weather history info for: " . $location;
  }
  $request = array();
  $request['type'] = "weather-history";
  $request['location'] = $location;
  $request['date'] = $date;
  $request['message'] = $msg;
  $response = $client->send_request($request);
  echo "client received response: ".PHP_EOL;
  echo "\n\n";
  return $response;
}

function amqpGameResults($winner, $loser, $winscore, $losescore, $wweather, $lweather)
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
  $request['wweather'] = $wweather;
  $request['lweather'] = $lweather;
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

function amqpSuggestOp($username)
{
  $client = new rabbitMQClient("testRabbitMQ.ini","testServer");
  if (isset($argv[1]))
  {
    $msg = $argv[1];
  }
  else
  {
    $msg = "Suggest opponent";
  }
  $request = array();
  $request['type'] = "suggest";
  $request['username'] = $username;
  $request['message'] = $msg;
  $response = $client->send_request($request);
  echo "client received response: ".PHP_EOL;
  echo "\n\n";
  return $response;
}

function amqpLog($data)
{
  logData($data); //Put data on the webserver logs first
  $client = new rabbitMQClient("testRabbitMQ.ini","testServer");
  if (isset($argv[1]))
  {
    $msg = $argv[1];
  }
  else
  {
    $msg = $data;
  }
  $request = array();
  $request['type'] = "logger";
  $request['message'] = $msg;
  $response = $client->send_request($request);
}

function amqpProfile($username)
{
  $client = new rabbitMQClient("testRabbitMQ.ini","testServer");
  if (isset($argv[1]))
  {
    $msg = $argv[1];
  }
  else
  {
    $msg = "Profile info for: $username";
  }
  $request = array();
  $request['type'] = "profile";
  $request['username'] = $username;
  $request['message'] = $msg;
  $response = $client->send_request($request);
}

function amqpBalance($username)
{
  $client = new rabbitMQClient("testRabbitMQ.ini","testServer");
  if (isset($argv[1]))
  {
    $msg = $argv[1];
  }
  else
  {
    $msg = "Checking balance for: $username";
  }
  $request = array();
  $request['type'] = "balance";
  $request['username'] = $user;
  $request['message'] = $msg;
  $response = $client->send_request($request);
}

function amqpTransaction($username, $item)
{
  $client = new rabbitMQClient("testRabbitMQ.ini","testServer");
  if (isset($argv[1]))
  {
    $msg = $argv[1];
  }
  else
  {
    $msg = "Checking balance for: $username";
  }
  $request = array();
  $request['type'] = "transaction";
  $request['username'] = $user;
  $request['item'] = $item;
  $request['message'] = $msg;
  $response = $client->send_request($request);
}

function amqpChangeName($username, $password, $change)
{
  $client = new rabbitMQClient("testRabbitMQ.ini","testServer");
  if (isset($argv[1]))
  {
    $msg = $argv[1];
  }
  else
  {
    $msg = "Changing username for $username to $change";
  }
  $request = array();
  $request['type'] = "change-name";
  $request['username'] = $username;
  $request['password'] = $password;
  $request['change'] = $change;
  $request['message'] = $msg;
  $response = $client->send_request($request);
}

function amqpChangePassword($username, $password, $change)
{
  $client = new rabbitMQClient("testRabbitMQ.ini","testServer");
  if (isset($argv[1]))
  {
    $msg = $argv[1];
  }
  else
  {
    $msg = "Changing password for $username";
  }
  $request = array();
  $request['type'] = "change-pass";
  $request['username'] = $username;
  $request['password'] = $password;
  $request['change'] = $change;
  $request['message'] = $msg;
  $response = $client->send_request($request);
}

