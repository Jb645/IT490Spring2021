<?php
session_start();
?>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="WebpageStyle.css">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css"
			  rel="stylesheet"
			  integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6"
			  crossorigin="anonymous">
		<title>Successful Login</title>
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
	<body class="body Account">

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

	<main>
		<p id = "Welcome">Welcome: <?php echo $_SESSION['login']; ?></p> <br>
	</main>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"
			integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf"
			crossorigin="anonymous"></script>
	</body>
</html>
<?php 
ini_set('display_errors',1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_DEPRECATED);

if(isset ($_SESSION['login']) && !empty($_SESSION['login']))
{
	//echo "Session is good.\n";
}
else
{
	header("Location: index.php");
}	
?> 
