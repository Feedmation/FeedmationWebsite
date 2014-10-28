<?php
	/*session_start();
	$loggedIn = empty($_SESSION['user']) ? false : $_SESSION['user'];
	if ($loggedIn == false) {
		header("Location: index.php");
		exit;
	}*/
require_once "loginFunctions.php";
//include_once 'assets/php_functions/phpFunctions.php';
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
		}
	</style>
</head>

<body>
	<h3>Pet Chart (Test)</h3>
	<!-- Chart.js global settings -->
	<script>
	</script>
	
	<!-- Get pet stats -->
	<?php
		$dbConn = dbconnect();
	
		// Select feeder
		$selectFeeders = "SELECT * FROM $GLOBALS[schema].feeders WHERE user_email = $1";
		$selectFeedersPrep = pg_prepare($dbConn, "feeders", $selectFeeders);
		if($selectFeedersPrep) {
			$feedersResult = pg_execute($dbConn, "feeders", array("meow@meow.com"));  //-- HARD CODED --//
		} else {
			echo "Could not sanitize user name. Try again later.";
		}
		if($feedersResult) {
			$feeders = '';
			while($row = pg_fetch_assoc($feedersResult)) {
				$feeders .= $row['feeder_id'];
			}
			pg_free_result($feedersResult);
		} else {
			echo "Could not query for Pet Feeders. Try refreshing the page";
		}
		
		// Select pet stats
		$petStatsQuery = "SELECT * FROM $GLOBALS[schema].stats WHERE feeder_id = $1";
		$petStatsPrep = pg_prepare($dbConn, "petStatsQuery", $petStatsQuery);
		if($petStatsPrep) { 
			$petStatsResult = pg_execute($dbConn, "petStatsQuery", array($feeders));	//-- HARD CODED --//
		} 
		else 
		{
			echo "<p>Couldn't get info of feeder with id $feeders. </p>";  //-- HARD CODED --//
		}
		if($petStatsResult) {
		$petStats = array();
		while($row = pg_fetch_assoc($petStatsResult)) {
			array_push($petStats, $row);
		}
		pg_free_result($petStatsResult);
		} else {
			echo "Could not query for Pet stats. Try refreshing the page";
		}
		
		// Parse stats data
		$petWeight = array();
		foreach ($petStats as $num=>$stat){
			array_push($petWeight,$petStats[$num]['petweight']);
		}
	?>
	
	<!-- Canvas on which the pet chart will be drawn -->
	<canvas id="petChart"></canvas>
	
	<!-- Draw chart -->
	<?php
		$string = "<script>
			var data = {
				labels: ['10/11','10/12','10/13','10/14','10/15','10/16','10/17','10/18','10/19'],  //-- HARD CODED --//
				datasets: [
					{
						label: 'Pet Weight',
						fillColor: 'rgba(220,220,220,0.2)',
						strokeColor: 'rgba(220,220,220,1)',
						pointColor: 'rgba(220,220,220,1)',
						pointStrokeColor: '#fff',
						pointHighlightFill: '#fff',
						pointHighlightStroke: 'rgba(220,220,220,1)',
						data: [";
						foreach ($petWeight as $weight)
							$string += "'$weight',";
						rtrim($string,',');
						$string += "]
					}
				]
			};
			
			var ctx = document.getElementById('petChart').getContext('2d');
			var newChart = new Chart(ctx).Line(data);
		
		</script>";
	var_dump($string);
	echo $string;
	?>
</body>

</html>
