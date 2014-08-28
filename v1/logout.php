<?php
	session_start();
	$_SESSION = array(); // unset session variables
	session_destroy(); // destroy session
	header("Location: index.php", true, 302);  //redirects back to index.php
	exit;
?>
