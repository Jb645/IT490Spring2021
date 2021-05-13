<html>
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="WebpageStyle.css">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css"
			  rel="stylesheet"
			  integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6"
			  crossorigin="anonymous">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Weather Pong - Login</title>
	</head>
	<body class="body create">
	<main>
		<h2 class="Welcome"> Weather Pong - Login </h2>
	</main>
	<main>
		<form name="loginform" id="myForm" method="POST" autocomplete = "off">
			<label for="username">Username: </label>
			<input type="username" id="username" name="username" placeholder="Enter Username"/>
			<label for="pass">Password: </label>
			<input type="password" id="pass" name="password" placeholder="Enter password"/>
			<input type="submit" value="Login"/>
		</form>
		<br><br> <a href="account_create.php"> Create an account </a>
	</main>

	<main class="trueMain"> <?php
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
		?> </main>
	</body>
</html>

