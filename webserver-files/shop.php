<?php
session_start();
?>
<html>
	<head>
		<title>Shop </title> 
	</head>
	<body>
	<form name="shop" id="shop" method="POST">
	<label for="shop">Shop: </label> <br>
	<input type="radio" id="hat1" name="hat" value="hat1"/> hat1 <br>
	<input type="radio" id="hat2" name="hat" value="hat2"/> hat2<br>
	<input type="radio" id="hat3" name="hat" value="hat3"/> hat3<br>
	<input type="radio" id="hat4" name="hat" value="hat4"/> hat4<br>
	<input type="radio" id="hat5" name="hat" value="hat5"/> hat5<br>
	<input type="submit" value="Submit"/>
		</form>
	</body>
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
<a href = "loggedin.php"> Back</a> <br>
<body>
</html>
