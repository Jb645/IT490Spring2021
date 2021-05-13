<?php
session_start();
?>
<html>
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="WebpageStyle.css">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css"
			  rel="stylesheet"
			  integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6"
			  crossorigin="anonymous">
		<title>Friends</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- Slick Nav-->
		<link rel="stylesheet" href="css/slicknav.css">
		<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
		<script src="js/jquery.slicknav.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function (){
				$('#nav_menu').slicknav({prependTo: "#mobile_menu"});
			});
		</script>
	</head>
	<body class="misc">
	<nav id="mobile_menu"></nav>
	<nav class="container" id="nav_menu">
		<ul>
			<li><a href = "leaderboards.php"> Leaderboards </a></li>
			<li><a href = "matchmaking.php"> MatchMaking </a></li>
			<li><a href = "shop.php"> Store </a></li>
			<li><a href = "chat.php"> Chat </a></li>
			<li><a href="#"> Profile </a>
				<ul>
					<li><a href="#">Achievements</a></li>
					<li><a href="Account.html">Account</a></li>
					<li><a href="modify-friends.html">Add Friends</a></li>
				</ul>
			</li>
			<li><a href = "exit.php"> Exit </a></li>

		</ul>
		<br>
		<br>
	</nav>

	<p id="Welcome">Friends list:</p>
	<main class="body">
	<form name="add" id="addForm" method="POST">
	<label for="addfriend">Add friend: </label>
	<input type="addfriend" id="addfriend" name="addfriend" placeholder="Add friend"/>
	<input type="submit" value="Add friend"/>
		</form>

	<br>

	<form name="remove" id="removeForm" method="POST">
	<label for="removefriend">Remove friend: </label>
	<input type="removefriend" id="removefriend" name="removefriend" placeholder="Remove friend"/>
	<input type="submit" value="Remove friend"/>
		</form>
	</main>

	<main class="trueMain">ini_set('display_errors',1);
                           ini_set('display_startup_errors', 1);
                           error_reporting(E_ALL ^ E_DEPRECATED);

                           require("testRabbitMQClient.php");

                           $username = $_SESSION['login'];

                           if(isset ($_SESSION['login']) && !empty($_SESSION['login']))
                           {
                           	//echo "Session is good.\n";
                           }
                           else
                           {
                           	header("Location: index.php");
                           }

                           if(isset($_POST['addfriend']) && !isset($_POST['removefriend']) && !empty($_POST['addfriend'])){
                           	$friendToAdd = preg_replace('/[^A-Za-z0-9]/', "",$_POST['addfriend']);

                           	$added = amqpAddFriend($username, $friendToAdd);
                           	if($added)
                           		echo $friendToAdd . " has been added";
                           	else
                           		echo "Failed to add: " . $friendToAdd;
                           }

                           if(isset($_POST['removefriend']) && !isset($_POST['addfriend']) && !empty($_POST['removefriend'])){
                           	$friendToRm = preg_replace('/[^A-Za-z0-9]/', "",$_POST['removefriend']);

                           	$removed = amqpRmFriend($username, $friendToRm);
                           	var_dump($removed);
                           	if($removed)
                           	{
                           		echo $removed . " has been removed";
                           	}
                           	else
                           	{
                           		echo "Failed to remove: " . $friendToRm;
                           	}
                           }
                           ?> </main>
	<footer><a href = "friends.php"> Back</a> </footer>

	</body>
</html>
<?php 

