<?php
session_start();
?>
<html>
	<head>
		<title>Successful Login</title>
	</head>
	<body>
	<br>Welcome: <?php echo $_SESSION['login']; ?> <br>
	<a href = "leaderboards.php"> Leaderboards </a> <br>
	<a href = "matchmaking.php"> Suggest opponent </a> <br>
	<a href = "exit.php"> Exit </a> <br>
	</body>
</html>
<?php 
ini_set('display_errors',1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_DEPRECATED);

if(isset ($_SESSION['login']) && !empty($_SESSION['login']))
{
	echo "Session is good.\n";
}
else
{
	header("Location: index.php");
}	
?> 
