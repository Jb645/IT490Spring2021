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
	<label for="removefriend">Add friend: </label>
	<input type="removefriend" id="removefriend" name="removefriend" placeholder="Remove friend"/>
	<input type="submit" value="Remove friend"/>
		</form>
		
	<a href = "loggedIn.php"> Back</a> <br>
	</body>
</html>
<?php 
ini_set('display_errors',1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_DEPRECATED);

if(isset ($_SESSION['login']) && !empty($_SESSION['login']))
{
	echo "Session is good.\n";
}
else
{
	header("Location: index.php");
}
if(isset($_POST['addfriend']) && !isset($_POST['removefriend']) && !empty($_POST['addfriend'])){
	$added = $_POST['addfriend'];


	echo $added . " has been added";
}

if(isset($_POST['removefriend']) && !isset($_POST['addfriend']) && !empty($_POST['removefriend'])){
	$removed = $_POST['removefriend'];


	echo $removed . " has been removed";
}
?> 
