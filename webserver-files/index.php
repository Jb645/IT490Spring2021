<html>
	<head>
		<title>Weather Pong - Login</title>
	</head>
	<body>
	<h1> Weather Pong - Login </h1>
		<form name="loginform" id="myForm" method="POST" autocomplete = "off">
			<label for="username">Username: </label>
			<input type="username" id="username" name="username" placeholder="Enter Username"/>
			<label for="pass">Password: </label>
			<input type="password" id="pass" name="password" placeholder="Enter password"/>
			<input type="submit" value="Login"/>
		</form>
		<br><br> <a href="account_create.php"> Create an account </a>
	</body>
</html>
<?php 
ini_set('display_errors',1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_DEPRECATED);
require("testRabbitMQClient.php");
session_start();
$salt = 'DoN0tP4s5My5erv3r';


if(isset($_POST['username']) && isset($_POST['password']) && !empty($_POST['password'])){
	$username = preg_replace('/[^A-Za-z0-9]/', "", $_POST['username']);
	$password = preg_replace('/\s+/', '', $_POST['password']);
	$password = password_hash($password, PASSWORD_BCRYPT);
	$password = chop($password, $salt);

	logData("Login attempted for: $username");
	try {
		$response = amqpLoginRequest($username, $password);
	}
	catch(Exception $e){
		echo $e->getMessage();
		logData($e->getMessage());
		exit();
	}
	logData("Login attempted for: $username");
	if($response)
	{
		$_SESSION['login'] = $username;
		logData("login succeeded for: $username");
		header("Location: loggedIn.php");
	}
	else
	{
		logData("login failed for: $username");
		echo "login failed";
	}
}
?> 
