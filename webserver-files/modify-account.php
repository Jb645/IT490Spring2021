<?php
session_start();
?>
<html>
	<head>
		<title>Modify account</title> 
	</head>
	<body>
	
	<br>Modify account:<br><br>
	<form name="modifyAccount" id="modifyAccount" method="POST">
	<label for="changeUser">Change Username: </label>
	<input type="changeUser" id="changeUser" name="changeUser" placeholder="New name"/>
	<br>
	
	<label for="changePass">Change Password: </label>
	<input type="changePass" id="changePass" name="changePass" placeholder="New password"/>
	<br>
	
	<label for="oldPass"> Old Password: </label>
	<input type="oldPass" id="oldPass" name="oldPass" placeholder="Old password"/> <br>
	<input type="submit" value="Submit"/>
	</form>
	Please enter your old/current password to confirm changes.<br><br>
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
	//echo "Session is good.\n";
}
else
{
	header("Location: index.php");
}

$username = $_SESSION['login'];

if(isset($_POST['changeUser']) && empty($_POST['changePass']) && !empty($_POST['changeUser']) && isset($_POST['oldPass']) && !empty($_POST['oldPass'])){
	
	$changeName = $_POST['changeUser'];
	if(preg_match("[\W]", $changeName) || preg_match('/\s+/', $username))
	{
		echo "Username cannot contain symbols or spaces";
		exit();
	}
	$password = $_POST['oldPass'];
	if(preg_match('/\s+/', $password))
	{
		echo "Password cannot contain spaces";
		exit();
	}

	$change = amqpChangeName($username, $password, $changeName);
	if($change)
		echo "$changeName is your new username";
	else
		echo "Failed to change name to $changeName";
}
else if(isset($_POST['changePass']) && empty($_POST['changeUser']) && !empty($_POST['changePass']) && isset($_POST['oldPass']) && !empty($_POST['oldPass'])){
	
	$changePass= $_POST['changePass'];
	if(preg_match('/\s+/', $changePass))
	{
		echo "Password cannot contain spaces";
		exit();
	}
	$changePass = password_hash($changePass, PASSWORD_BCRYPT);
	
	$password = $_POST['oldPass'];
	if(preg_match('/\s+/', $password))
	{
		echo "Password cannot contain spaces";
		exit();
	}
	$password = password_hash($password, PASSWORD_BCRYPT);

	$change = amqpChangePassword($username, $password, $changePass);
	if($change)
		echo "Password updated";
	else
		echo "Failed to change password";
	}
else
{
  echo "You can only change one part of your account at a time";
}
?> 
<html><body> <br><br><a href = "loggedIn.php"> Back</a> </html></body>
