<?php
	session_start();
	$loggedIn = empty($_SESSION['user']) ? false : $_SESSION['user'];
	if ($loggedIn == false) {
		header("Location: index.php");
		exit;
	}
	
include_once '../../loginFunctions.php';
?>
<script>
	 $('#addFeederForm').on('submit', function (e) {

		e.preventDefault();
          
		$.ajax({
		url: 'assets/form_processing/insert_feeder.php',
		type: "POST",
		dataType: 'text',
		data: $("#addFeederForm").serialize(),
		success: function(data) {
			$("#main-content").html(data);
		}
	});	
	});
</script>

<?php

	$dbConn = dbconnect();
	$feederId = $_POST['feederId'];
	$feederName = $_POST['feederName'];
	$cost = (empty($_POST['cost'])) ? 0 : $_POST['cost'];
	$weight = (empty($_POST['weight'])) ? 0 : $_POST['weight'];
	
	//check if the feederId already exists in the DB
	$feederIdSelect = "SELECT * FROM $GLOBALS[schema].feeders WHERE feeder_id = $1";
	
	$feederIdPrep = pg_prepare($dbConn, "feederSelect", $feederIdSelect);
	
	if($feederIdPrep) {
		$feederIdResult = pg_execute($dbConn, "feederSelect", array($feederId));
	} else {
		echo "<p>Couldn't sanitize query for Feeder ID.</p>";
	}
	
	if(pg_num_rows($feederIdResult) == 0) {
		
		//no match found, proceed with insert	
		$feederInsert = "INSERT INTO $GLOBALS[schema].feeders (user_email, feeder_id, feeder_name, food_bag_cost, food_bag_weight) VALUES ($1, $2, $3, $4, $5)";
		
		$feederPrep = pg_prepare($dbConn, "insertFeeder", $feederInsert);
		
		if($feederPrep) {
			$feederResult = pg_execute($dbConn, "insertFeeder", array($_SESSION['user'], $feederId, $feederName, $cost, $weight));	
			pg_free_result($feederResult);
		} else {
			echo "<p>Couldn't insert values for feeder. Try again later.</p>";
		}

		//this function will refresh the list of feeders 
		//by querying the DB and printing out the formatted HTML.
		//it is located in assets/php_functions/phpFunctions.php
		$data = populateFeeders();
		echo $data;
		
	} else {
		//the feederId already exists. reprint the form
		//and display an error message
		echo "feederExists";
			
	}


?>












