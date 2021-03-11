<?php 
ini_set('display_errors',1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_DEPRECATED);
	require("testRabbitMQClient.php");
	
	$winner = $_POST['winner'];
	$loser = $_POST['loser'];
	$winscore = $_POST['wscore'];
	$losescore = $_POST['lscore'];
	$wweather = $_POST['wweather'];
	$lweather = $_POST['lweather'];

	
	$response = amqpGameResults($winner, $loser, $winscore, $losescore, $wweather, $lweather);

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
