#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$username = 'root';
$password = '12345';


function customError($errno, $errstr)
{
  $logs = fopen("logs.txt", "a") or die("Unable to open file."); //open logs
  fwrite($logs, "<b>ERROR:</b> [$errno] $errstr<br>");
  echo "<b>ERROR:</b> [$errno] $errstr<br>";
}

function doLogin($username,$password)
{
  echo "got into the login function";
    $mydb = new mysqli('127.0.0.1',$username,$password,'testdb');
    if ($mydb->errno != 0)
    {
    	echo "failed to connect to database: ". $mydb->error . PHP_EOL;
      return false;
    	exit(0);
    }

    echo "successfully connected to database".PHP_EOL;

    $query = "select * from users;";

    $response = $mydb->query($query);
    if ($mydb->errno != 0)
    {
    	echo "failed to execute query:".PHP_EOL;
    	echo __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
    	exit(0);
    }
    $numrows = mysqli_num_rows($response);
    echo "we got $numrows rows from the query".PHP_EOL;

    while ($row = $response->fetch_row())
    {
    	print_r($row);
    }
    echo "test complete".PHP_EOL;
    return true;
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
<<<<<<< HEAD
    fwrite($logs, "ERROR: unsupported message type");	
=======
    fwrite($logs, "ERROR: unsupported message type");
>>>>>>> 5425a95dcd3a58d857f60a69c49cfc9657781342
    return "ERROR: unsupported message type";
  }
  fwrite($logs, date("\nh:i:sa")); //write time of request
  fwrite($logs, "\n___________\n"); //formatting
<<<<<<< HEAD
  fwrite($logs, $info); //take caputured var dump and write to file 
=======
  fwrite($logs, $info); //take caputured var dump and write to file
>>>>>>> 5425a95dcd3a58d857f60a69c49cfc9657781342
  fclose($logs);//close
  switch ($request['type'])
  {
    case "login":	  
      return doLogin($request['username'],$request['password']);
    case "validate_session":
      return doValidate($request['sessionId']);
      echo 'sessionId';
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed. Good job");
}

set_error_handler("customError",E_USER_WARNING);

$server = new rabbitMQServer("testRabbitMQ.ini","testServer");

echo "testRabbitMQServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
$server->process_requests('doLogin');
echo "testRabbitMQServer END".PHP_EOL;
exit();
?>
