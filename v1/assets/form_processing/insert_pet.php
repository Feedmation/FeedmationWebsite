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
	
	
	//check to see if user has an available slot
	$slotAmountSelect = "SELECT * FROM $GLOBALS[schema].rfid WHERE feeder_id = $1";
	
	$slotAmountPrep = pg_prepare($dbConn, "slotAmount", $slotAmountSelect);
	
	if($slotAmountPrep) 
	{
		$slotAmountResult = pg_execute($dbConn, "slotAmount", array($feederId));
		
		//if users has already reached four pets, then throw error and stop insert
		if(pg_num_rows($slotAmountResult) >= 4) {
		
			echo "<p>You have reached the max limit of four pets!</p>";
			pg_free_result($slotAmountResult);
		
		} else 
		{ //else locating an open slot between 1 and 4
		
			$openSlot;
			$match = false;
			$keepSearching = true;
			
			for ($i=1; $i<=4; $i++) {

				if ($keepSearching == true) {
				
					while( ($tagRow = pg_fetch_assoc($slotAmountResult)) ) {
						if ($i == $tagRow['tag_slot']) {
							$match = true; 
						}
					}
			
					if ($match==true) {
						$keepSearching = true;
					} else {
						$keepSearching = false;
						$openSlot = $i;
					}
				}
			}
			
			pg_free_result($slotAmountResult);
		
			if ( $openSlot != NULL ) {
				//check if the pet tag already exists in the DB
				$tagIdSelect = "SELECT * FROM $GLOBALS[schema].rfid WHERE tag_id = $1 AND user_email = $2";
	
				$tagIdPrep = pg_prepare($dbConn, "tagSelect", $tagIdSelect);
	
				if($tagIdPrep) {
					$tagIdResult = pg_execute($dbConn, "tagSelect", array($tagNumber, $_SESSION['user']));
				
					if(pg_num_rows($tagIdResult) == 0) {
		
						//no match found, proceed with insert	
						$petInsert = "INSERT INTO $GLOBALS[schema].rfid VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11)";
		
						$petPrep = pg_prepare($dbConn, "insertPet", $petInsert);
		
						if($petPrep) {
							$petResult = pg_execute($dbConn, "insertPet", array($tagNumber, $feederId, $startSlot1, $endSlot1, $startSlot2, $endSlot2, $petName, $_SESSION['user'], "TRUE", $feedAmt, $openSlot));	
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
				
				} else {
					echo "<p>Couldn't sanitize query for Tag ID.</p>";
				}
			} else {
				echo "<p>Problem finding slot for pet</p>";
			}
		}
		
	} else {
		echo "<p>Couldn't sanitize query for Tag ID.</p>";
	}

?>












