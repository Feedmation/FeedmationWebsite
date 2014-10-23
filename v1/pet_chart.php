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
		canvas#petChart{
			padding-left: 0;
			padding-right: 0;
			margin-left: auto;
			margin-right: auto;
			display: block;
			width: 50%;
			height:50%;
		}
	</style>
</head>

<body>
	<!-- Chart.js global settings -->
	<script>
		Chart.defaults.global.responsive = true;
	</script>
	
	<!-- Canvas on which the pet chart will be drawn -->
	<canvas id="petChart" width="400" height="300"></canvas>
	
	<!-- Draw chart -->
	<script>
		var data = {
			labels: ["January", "February", "March", "April", "May", "June", "July"],
			datasets: [
				{
					label: "My First dataset",
					fillColor: "rgba(220,220,220,0.2)",
					strokeColor: "rgba(220,220,220,1)",
					pointColor: "rgba(220,220,220,1)",
					pointStrokeColor: "#fff",
					pointHighlightFill: "#fff",
					pointHighlightStroke: "rgba(220,220,220,1)",
					data: [65, 59, 80, 81, 56, 55, 40]
				},
				{
					label: "My Second dataset",
					fillColor: "rgba(151,187,205,0.2)",
					strokeColor: "rgba(151,187,205,1)",
					pointColor: "rgba(151,187,205,1)",
					pointStrokeColor: "#fff",
					pointHighlightFill: "#fff",
					pointHighlightStroke: "rgba(151,187,205,1)",
					data: [28, 48, 40, 19, 86, 27, 90]
				}
			]
		};
		
		var ctx = document.getElementById("petChart").getContext("2d");
		var newChart = new Chart(ctx).Line(data);
		
		
	</script>
	
</body>

</html>
