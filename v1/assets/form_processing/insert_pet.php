<?php
	session_start();
	$loggedIn = empty($_SESSION['user']) ? false : $_SESSION['user'];
	if ($loggedIn == false) {
		header("Location: index.php");
		exit;
	}
	
include_once '../../loginFunctions.php';
?>

<?php

	$dbConn = dbconnect();
	$feederId = $_POST['feederId'];
	$petName = $_POST['name'];
	$tagNumber = $_POST['number'];
	$startSlot1 = intval(substr($_POST['startTime1'], 0, strpos($_POST['startTime1'], ":")));
	$startSlot2 = intval(substr($_POST['startTime2'], 0, strpos($_POST['startTime2'], ":")));
	$endSlot1 = intval(substr($_POST['endTime1'], 0, strpos($_POST['endTime1'], ":")));
	$endSlot2 = intval(substr($_POST['endTime2'], 0, strpos($_POST['endTime2'], ":"))) + 12;
	$feedAmt = $_POST['feedAmount'];
	
	if($startSlot1 == 12) {
		$startSlot1 -= 12;
	}
	
	if($startSlot2 != 12) {
		$startSlot2 += 12;	
	}
	
	//check if the pet tag already exists in the DB
	$tagIdSelect = "SELECT * FROM $GLOBALS[schema].rfid WHERE tag_id = $1 AND user_email = $2";
	
	$tagIdPrep = pg_prepare($dbConn, "tagSelect", $tagIdSelect);
	
	if($tagIdPrep) {
		$tagIdResult = pg_execute($dbConn, "tagSelect", array($tagNumber, $_SESSION['user']));
	} else {
		echo "<p>Couldn't sanitize query for Tag ID.</p>";
	}
	
	if(pg_num_rows($tagIdResult) == 0) {
		
		//no match found, proceed with insert	
		$petInsert = "INSERT INTO $GLOBALS[schema].rfid VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10)";
		
		$petPrep = pg_prepare($dbConn, "insertPet", $petInsert);
		
		if($petPrep) {
			$petResult = pg_execute($dbConn, "insertPet", array($tagNumber, $feederId, $startSlot1, $endSlot1, $startSlot2, $endSlot2, $petName, $_SESSION['user'], "TRUE", $feedAmt));	
			pg_free_result($petResult);
		} else {
			echo "<p>Couldn't insert values for pet. Try again later.</p>";
		}

		//this function will refresh the list of feeders 
		//by querying the DB and printing out the formatted HTML.
		//it is located in assets/php_functions/phpFunctions.php
		$data = populateFeeders();
		echo $data;
		
	} else {
		//the feederId already exists. reprint the form
		//and display an error message
		echo "petExists";
			
	}


?>












