<html>
	<head>
		<title>Make account</title>
	</head>
	<body>
	<h1> Create an account</h1>
		<form name="create" id="myForm" method="POST">
			<label for="username">Username: </label>
			<input type="username" id="username" name="username" placeholder="Enter Username"/>
			<label for="pass">Password: </label>
			<input type="password" id="pass" name="password" placeholder="Enter password"/>
			<input type="submit" value="Create"/>
		</form>
		<br><br> <a href="index.php"> Login</a>
	</body>
</html>
<?php 
ini_set('display_errors',1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_DEPRECATED);
session_start();

if(isset($_POST['username']) && isset($_POST['password']) && !empty($_POST['password'])){
	$password = $_POST['password'];
	$username = $_POST['username'];
	
	require("testRabbitMQClient.php");
	try {
		//$response = amqpLoginRequest($username, $password);
	}
	catch(Exception $e){
		echo $e->getMessage();
		exit();
	}
	
	if($response == 0)
	{
		echo "Account created!";
	}
	else
	{
		echo "Name is taken";
	}
}	
?> 
