#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('logger.php');
require_once('serverFunctions.php');

function requestProcessor($request)
{
  var_dump($request);
  logRequest($request); //Writes the request info to logs.txt
  echo "received request".PHP_EOL;
  if(!isset($request['type']))
  {
    logData("ERROR: unsupported message type"); //Writes error to logs	
    return "ERROR: unsupported message type";
  }
  switch ($request['type'])
  {
    case "login":	  
      return doLogin($request['username'],$request['password']);
    case "logger":
      return logData($request['message']);
    case "validate_session":
      return doValidate($request['sessionId']);
    case "create_account":
      return createAccount($request['username'], $request['password']);
    case "weather":
       return $weather_output = getWeather( $request['location']);
    case "weather-history":
       return $weather_output = getWeatherHistory( $request['location'], $request['date']);
    case "results":
      return $results = insertResults($request);
    case "friendslist":
      return $friends = getFriends($request['username']);
    case "addfriend":
      return addFriend($request['username'], $request['target']);
    case "rmfriend":
      return rmFriend($request['username'], $request['target']); 
    case "get-leaderboard":
      return $board = getLeaderboard();
    case "suggest":
      return $suggestion = getSuggestion($request['username']);
    case "profile":
      return $profile = getProfile($request['username']);
    case "change-name":
      return;
    case "change-pass":
     return;
    case "get-wins":
     return $wins = getWins($request['username']);
  }

  if(isset($weather_output))
	  return $weather_output;
  if(isset($friends))
	  return $friends;
  if(isset($board))
	  return $board;
  if(isset($suggestion))
	  return $suggestion;
  
  return array("returnCode" => '0', 'message'=>"Server received request and processed. Good job");
}

$server = new rabbitMQServer("testRabbitMQ.ini","testServer");

echo "testRabbitMQServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;
exit();
?>

