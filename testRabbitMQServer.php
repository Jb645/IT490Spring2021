#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

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
  var_dump($request);
  ob_start();
  var_dump($request);
  $info = ob_get_clean();
  if(!isset($request['type']))
  {
    return "ERROR: unsupported message type";
  }
  fwrite($logs, date("\nh:i:sa"));  
  fwrite($logs, "\n___________\n");
  fwrite($logs, $info);
  // fwrite($logs, implode("\n",$request));
 /* foreach ($request as $value)
  {
  	fwrite($logs, $value); //write message to log file
  	fwrite($logs, "\n"); //newline
  }*/
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

$server = new rabbitMQServer("testRabbitMQ.ini","testServer");

echo "testRabbitMQServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;
exit();
?>

