<?php
session_start();
?>
<html>
	<head>
		<title>Profile: </title> 
	</head>
	<body>
	<br>Profile:<br><br>
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
	echo "Session is good.\n";
}
else
{
	header("Location: index.php");
}

if(isset($_GET['search']) && !empty($_GET['search'])){
	$user = preg_replace('/[^A-Za-z0-9]/', "",$_GET['search']);
}
else
{
	$user = $username;
}

echo $user;

?> 

<html>
	<body>
	<form name="search" id="search" method="GET">
	<label for="search">Search user: </label>
	<input type="search" id="search" name="search" placeholder="Search user"/>
	<input type="submit" value="search"/>
		</form>
	<a href = "loggedin.php"> Back</a> <br>
	</body>
</html>

