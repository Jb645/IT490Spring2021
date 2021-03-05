#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('logger.php');

function doLogin($username,$password)
{
    // lookup username in databas
    // check password
    return true;
    //return false if not valid
}

function getWeather($location)
{
  $output = shell_exec("python3 RAbbitMQ-TnE/WeatherPONG.py ". $location);
  if(!isset ($output))
  {
    logData("No data recieved from weather API");	  
  }
  $output = preg_split('/\r\n|\n/', $output);
  $weather = array((float) filter_var( $output[0], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION), substr($output[3],15));
  LogData("Temp: " . $weather[0] ." | Desc: ". $weather[1] ."\n");
  return $weather;
}

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
    case "validate_session":
      return doValidate($request['sessionId']);
    case "weather":
      return $weather_output = getWeather( $request['location']);
  }

  if(isset($weather_output))
	  return $weather_output;
  
  return array("returnCode" => '0', 'message'=>"Server received request and processed. Good job");
}

$server = new rabbitMQServer("testRabbitMQ.ini","testServer");

echo "testRabbitMQServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;
exit();
?>

