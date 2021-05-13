		<meta charset="UTF-8">
		<link rel="stylesheet" href="WebpageStyle.css">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css"
			  rel="stylesheet"
			  integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6"
			  crossorigin="anonymous">
		<title>Make account</title>
	</head>
	<body class="body create">
		<header class="create">
			<p class="Welcome"> Create an account</p>
		</header>


		<main>
			<div class="logins">
				<form name="create" id="myForm" method="POST">
					<label for="username">Username: </label>
					<input type="username" id="username" name="username" placeholder="Enter Username" autocomplete="off"/>
					<label for="pass">Password: </label>
					<input type="password" id="pass" name="password" placeholder="Enter password"/>
					<input type="submit" value="Create"/>
				</form>
			</div>
			<div class="login_button"><br> <a href="index.php"> Login</a><br></div>

		</main>
        <main class="trueMain">
            $salt = 'DoN0tP4s5My5erv3r'
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
                $password = $password.$salt;
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
        </main>
	</body>
</html>
<?php 

