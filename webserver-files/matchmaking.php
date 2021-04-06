<?php
session_start();
?>
<html>
	<head>
		<title>Matchmaking</title>
	</head>
	<body>
	<br>:<br>
	<a href = "loggedIn.php"> Back</a> <br>
	</body>
</html>
<?php 
ini_set('display_errors',1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_DEPRECATED);

require("testRabbitMQClient.php");

if(isset ($_SESSION['login']) && !empty($_SESSION['login']))
{
	echo nl2br("Session is good\n");
	$opponent = amqpSuggestOp($_SESSION['login']);
	echo ("Opponent should be: $opponent");
}
else
{
	header("Location: index.php");
}
?> 
