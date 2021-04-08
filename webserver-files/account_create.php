<html>
	<head>
		<title>Make account</title>
	</head>
	<body>
	<h1> Create an account</h1>
		<form name="create" id="myForm" method="POST">
			<label for="username">Username: </label>
			<input type="username" id="username" name="username" placeholder="Enter Username" autocomplete="off"/>
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
	$username = $_POST['username'];
	if(preg_match("[\W]", $username) || preg_match('/\s+/', $username))
	{
		echo "Username cannot contain symbols or spaces";
		exit();
	}
	$password = $_POST['password'];
	if(preg_match('/\s+/', $password))
	{
		echo "Password cannot contain spaces";
		exit();
	}
	$password = password_hash($password, PASSWORD_BCRYPT);
	
	require("testRabbitMQClient.php");
	try {
		$response = amqpCreateAccount($username, $password);
	}
	catch(Exception $e){
		echo $e->getMessage();
		exit();
	}
	var_dump($response);
	if($response)
	{
		echo "Account created!";
	}
	else
	{
		echo "Name is taken";
	}
}
?> 
