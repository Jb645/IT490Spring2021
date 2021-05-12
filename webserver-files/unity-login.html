<html>
	<body>
	</body>
</html>
<?php 
ini_set('display_errors',1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_DEPRECATED);
session_start();

if(isset($_POST['username']) && isset($_POST['password']) && !empty($_POST['password'])){
	$username = preg_replace('/[^A-Za-z0-9]/', "", $_POST['username']);
	$password = preg_replace('/\s+/', '', $_POST['password']);
	$password = password_hash($password, PASSWORD_BCRYPT);
	
	require("testRabbitMQClient.php");
	try {
		$response = amqpLoginRequest($username, $password);
	}
	catch(Exception $e){
		echo $e->getMessage();
		exit();
	}
	if($response)
	{
		echo "|login success|";
	}
	else
	{
		echo "|login failed|";
	}
}	
?> 
