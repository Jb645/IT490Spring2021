<?php
session_start();
?>
<html>
	<head>
		<title>Goodbye.</title>
	</head>
	<body>
	<div class="login_button"><br> <a href="index.php">Login</a><br></div>

	</body>
</html>
<?php 
session_unset();
session_destroy;
?> 
