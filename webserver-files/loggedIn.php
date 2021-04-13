<?php
session_start();
?>
<html>
	<head>
		<title>Successful Login</title>
		<link rel="stylesheet" href="WebpageStyle.css">
	</head>
	<body>
	
	<nav class="nav">
		<ul>
			<li><a href = "leaderboards.php"> Leaderboards </a></li>
			<li><a href = "matchmaking.php"> Suggest opponent </a></li>
			<li><a href = "profile.php"> View profile </a></li>
			<li><a href = "friends.php"> View friends </a></li>
			<li><a href = "chat.php"> Chat </a></li>
			<li><a href = "shop.php"> Shop </a></li>
			<li><a href = "exit.php"> Exit </a></li>
		</ul>
		<br>
		<p class = "Welcome">Welcome: <?php echo $_SESSION['login']; ?></p> <br>
	</nav>
	
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
