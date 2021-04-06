<?php
session_start();
?>
<html>
	<head>
		<title>Leaderboards</title>
		<link rel="stylesheet" href="WebpageStyle.css">
	</head>
	<body>

	<header>
		<h2>Leaderboards:</h2>
	</header>

	<footer>
		<a href = "loggedIn.php"> Back</a> <br>
	</footer>

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
	$users = amqpLeaderboard();
	echo nl2br("\n\nTop 10: \n");
	echo nl2br("\nName | Wins | Losses\n--------------------------------");
	$count = 0;
	while($count < 10)
	{
		echo nl2br("\n" . ($count + 1) . ": $users[$count]");
		$count ++;
	}
}
else
{
	header("Location: index.php");
}	
?> 
