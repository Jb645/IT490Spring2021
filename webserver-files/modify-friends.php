<?php
session_start();
?>
<html>
	<head>
		<title>Friends</title> 
	</head>
	<body>
	
	<br>Friends list:<br><br>
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
		
	<a href = "friends.php"> Back</a> <br>
	</body>
</html>
<?php 
ini_set('display_errors',1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_DEPRECATED);

require("testRabbitMQClient.php");

$username = $_SESSION['login'];

if(isset ($_SESSION['login']) && !empty($_SESSION['login']))
{
	echo "Session is good.\n";
}
else
{
	header("Location: index.php");
}

if(isset($_POST['addfriend']) && !isset($_POST['removefriend']) && !empty($_POST['addfriend'])){
	$friendToAdd = $_POST['addfriend'];
	

	$added = amqpAddFriend($username, $friendToAdd);
	if($added)
		echo $friendToAdd . " has been added";
	else
		echo "Failed to add: " . $friendToAdd;
}

if(isset($_POST['removefriend']) && !isset($_POST['addfriend']) && !empty($_POST['removefriend'])){
	$friendToRm = $_POST['removefriend'];
	
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
?> 
