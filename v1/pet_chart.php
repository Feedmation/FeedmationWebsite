<?php
	session_start();
	$loggedIn = empty($_SESSION['user']) ? false : $_SESSION['user'];
	if ($loggedIn == false) {
		header("Location: index.php");
		exit;
	}
include_once 'loginFunctions.php';
?>
<!DOCTYPE html>
<html>

<head>
	<title>Pet Chart (Test)</title>

	<!-- Chart.js -->
	<script src="assets/Chart.js-master/Chart.min.js"></script>
	
	<!-- CSS -->
	<style>
		canvas {
			border: double;
			padding-left: 0;
			padding-right: 0;
			margin-left: auto;
			margin-right: auto;
			display: block;
			width: 20%;
			height:20%;
		}
	</style>
</head>

<body>
	<canvas id="petChart"></canvas>
</body>

</html>
