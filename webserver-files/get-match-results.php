<?php 
ini_set('display_errors',1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_DEPRECATED);
	require("testRabbitMQClient.php");
	
	$winner = $_GET['winner'];
	$loser = $_GET['loser'];
	$winscore = $_GET['wscore'];
	$losescore = $_GET['lscore'];
	$weather = $_GET['weather'];
	
	$response = amqpGameResults($winner, $loser, $winscore, $losescore, $weather);

	if(isset($response))
	{
		//echo ($response[0]);
		//echo ($response[1]);
	}
	else
	{
		echo "No response from server";
	}	
?> 
<html>
<body>
Match results
</body>
</html>
