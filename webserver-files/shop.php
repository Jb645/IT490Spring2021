<?php
session_start();
?>
<html lang="en">
	<head>
		<title>Shop </title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css"
			  rel="stylesheet"
			  integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6"
			  crossorigin="anonymous">		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="WebpageStyle.css">
		<link rel="stylesheet" href="css/slicknav.css">
		<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
		<script src="js/jquery.slicknav.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function (){
				$('#nav_menu').slicknav({prependTo: "#mobile_menu"});
			});
		</script>
	</head>
	<body class="shop">
	<nav id="mobile_menu"></nav>
	<nav class="container" id="nav_menu">
		<ul>
			<li><a href = "leaderboards.php"> Leaderboards </a></li>
			<li><a href = "matchmaking.php"> MatchMaking </a></li>
			<li><a href = "#"> Store </a></li>
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
	<main>
		<div class="container">
				<form name="shop" id="shop" method="POST">
					<label class ="title " for="shop">Shop: </label>


					<div class="row specialTitle shop-row">
						<div class="col">
							<input type="radio" id="hat1" name="hat" value="hat1">
								<label for="hat1"><img src="Images/shopHats/baseshop.png" alt="base Hat" height="100%"></label>
						</div>
						<div class="col">
							<input type="radio" id="hat2" name="hat" value="hat2">
								<label for="hat2"><img src="Images/shopHats/redshop.png" alt="red Hat" height="100%"></label>
						</div>
						<div class="col">
							<input type="radio" id="hat3" name="hat" value="hat3">
								<label for="hat3"><img src="Images/shopHats/greenshop.png" alt="green Hat" height="100%"></label>
						</div>
						<div class="col">
							<input type="radio" id="hat4" name="hat" value="hat4">
								<label for="hat4"><img src="Images/shopHats/whiteshop.png" alt="white Hat" height="100%"></label>
						</div>
						<div class="col">
							<input type="radio" id="hat5" name="hat" value="hat5">
								<label for="hat5"><img src="Images/shopHats/bestshop.gif" alt="upgraded Hat Hat" height="100%"></label>
						</div>
					</div>
				<input class="button" type="submit" value="Submit">
				</form>

		</div>
	</main>


	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"
			integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf"
			crossorigin="anonymous"></script>	</body>
</html>
<?php
ini_set('display_errors',1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_DEPRECATED);

require("testRabbitMQClient.php");


if(isset ($_SESSION['login']) && !empty($_SESSION['login']))
{
	//echo "Session is good.\n";
}
else
{
	header("Location: index.php");
}

 $username = $_SESSION['login'];

 //$balance = amqpBalance($username);
 $balance = 100;
 if ($balance == null)
   amqpLog("$username failed to get balance");
 else
 {
  // amqpLog("$username successfully retrieved balance");
   echo "Your balance is: $balance";
 }

if(isset($_POST["hat"]) && !empty($_POST["hat"]))
{
   $hat =  $_POST["hat"];

   switch($hat)
   {
     case 'hat1':
     {
	$price = 10;
	break;
     }
     case 'hat2':
     {
	$price = 25;
	break;
     }
     case 'hat3':
     {
	$price = 50;
	break;
     }
     case 'hat4':
     {
	$price = 100;
	break;
     }
     case 'hat5':
     {
	$price = 300;
	break;
     }
   }

   if($balance - $price < 0)
   {
	echo nl2br("\n\n You can't afford that");
   }
   else
   {
   	//amqpTransaction($username, $_POST['shop']);
	echo nl2br("\n\n You purchased: $hat");
   }
}
?>
<html>
<body>
<br><br>
<footer>
<a href = "loggedin.php"> Back</a> <br>
</footer>
</body>
</html>
