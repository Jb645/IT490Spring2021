<html>
	<head>
		<title>My Project - Login</title>
	</head>
	<body>
		<form name="loginform" id="myForm" method="POST">
			<label for="username">Username: </label>
			<input type="username" id="username" name="username" placeholder="Enter Username"/>
			<label for="pass">Password: </label>
			<input type="password" id="pass" name="password" placeholder="Enter password"/>
			<input type="submit" value="Login"/>
		</form>
	</body>
</html>
<?php 
ini_set('display_errors',1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if(isset($_POST['username']) && isset($_POST['password']) && !empty($_POST['password'])){
	$password = $_POST['password'];
	$username = $_POST['username'];
	
	//let's hash it
	//$pass = password_hash($pass, PASSWORD_BCRYPT);
	//echo "<br>$pass<br>";
	//it's hashed
	require("testRabbitMQClient.php");
	try {
		amqpLoginRequest($username, $password);
	}
	catch(Exception $e){
		echo $e->getMessage();
		exit();
	}
}
?> 
