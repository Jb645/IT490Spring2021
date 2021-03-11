<?php
session_start();
?>
<html>
	<head>
		<title>Leaderboards</title>
	</head>
	<body>
	<br>Leaderboards:<br>
	<a href = "loggedIn.php"> Back</a> <br>
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
if(isset($_POST['username']) && isset($_POST['password']) && !empty($_POST['password'])){
	$password = $_POST['password'];
	$username = $_POST['username'];

	echo "congrats";
}
   $mydb = dbConnect();
   $sql = "SELECT username, wins, losses FROM users";
   echo $mydb->query($sql);
   $mydb->close();
?> 
