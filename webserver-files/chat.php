<?php
session_start();
?>
<html>
	<head>
		<title>Chat</title>
	</head>
	<body>
	<br>Chat:<br>
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
	
?> 
