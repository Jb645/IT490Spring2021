<?php
session_start();
?>
<html>
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="WebpageStyle.css">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css"
			  rel="stylesheet"
			  integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6"
			  crossorigin="anonymous">
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
