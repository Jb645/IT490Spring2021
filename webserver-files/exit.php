<?php
session_start();
?>
<html>
	<head>
		<title>Goodbye.</title>
	</head>
	<body>
	<br>Goodbye<br>
	</body>
</html>
<?php 
session_unset();
session_destroy;
?> 
