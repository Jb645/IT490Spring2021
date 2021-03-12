<?php 
ini_set('display_errors',1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_DEPRECATED);
	require("testRabbitMQClient.php");
	
	$location = $_GET['location'];
	$date = $_GET['date'];
	
	$response = amqpWeatherHistory($location, $date);

	if(isset($response))
	{
		echo ("|".$response[0] ."|". $response[1]."|");
	}
	else
	{
		echo "No response from server";
	}	
?> 
<html>
<body>
Weather
</body>
</html>
