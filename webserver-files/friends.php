<?php
session_start();
?>
<html>
	<head>
		<title>Friends</title> 
	</head>
	<body>
	
	<br>Friends list:<br><br>
	</body>
</html>
<?php 
ini_set('display_errors',1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_DEPRECATED);

require("testRabbitMQClient.php");

$username = $_SESSION['login'];

if(isset ($_SESSION['login']) && !empty($_SESSION['login']))
{
	echo nl2br("Session is good\n");
	$friends = amqpFriendslist($username);
	echo nl2br("\n\nCurrent Friends: \n");
	$count = 0;
	while($count < count($friends))
	{
		echo nl2br("\n" . ($count + 1) . ": $friends[$count]");
		$count ++;
	}
}
else
{
	header("Location: index.php");
}
?> 
<html>
<body>
<br> <br>
<a href = "modify-friends.php"> Add/remove friends</a> <br>
<br><br>
<a href = "loggedIn.php"> Back</a> <br>
</body>
</html>
