#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function customError($errno, $errstr)
{
  $logs = fopen("logs.txt", "a") or die("Unable to open file."); //open logs
  fwrite($logs, "<b>ERROR:</b> [$errno] $errstr<br>");
  echo "<b>ERROR:</b> [$errno] $errstr<br>";
}

function doLogin($username,$password)
{
    // lookup username in databas
    // check password
    return true;
    //return false if not valid
}

function requestProcessor($request)
{
  $logs = fopen("logs.txt", "a") or die("Unable to open file."); //open logs
  echo "received request".PHP_EOL;
  var_dump($request); //display request info
  ob_start(); //Output buffering
  var_dump($request); //2nd var dump, captured by output buffering for logging
  $info = ob_get_clean(); //
  if(!isset($request['type']))
  {
    fwrite($logs, "ERROR: unsupported message type");
    return "ERROR: unsupported message type";
  }
  fwrite($logs, date("\nh:i:sa")); //write time of request
  fwrite($logs, "\n___________\n"); //formatting
  fwrite($logs, $info); //take caputured var dump and write to file
  fclose($logs);//close
  switch ($request['type'])
  {
    case "login":
      return doLogin($request['username'],$request['password']);
    case "validate_session":
      return doValidate($request['sessionId']);
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed. Good job");
}

set_error_handler("customError",E_USER_WARNING);

$server = new rabbitMQServer("testRabbitMQ.ini","testServer");

echo "testRabbitMQServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;
exit();
?>
