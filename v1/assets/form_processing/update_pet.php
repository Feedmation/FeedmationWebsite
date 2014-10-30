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
	
	//prepare the update statement
	$updatePet = "UPDATE $GLOBALS[schema].rfid SET slot_one_start=$1, slot_one_end=$2, slot_two_start=$3,
						slot_two_end=$4, pet_name=$5, has_changed=true, feed_amount=$6, feeder_id=$7 WHERE tag_id=$8 AND user_email=$9";
						
	$updatePetPrep = pg_prepare($dbConn, "petUpdate", $updatePet);
	
	if($updatePetPrep) {
		$result = pg_execute($dbConn, "petUpdate", array($startSlot1, $endSlot1, $startSlot2, $endSlot2, $petName, $feedAmt, $feederId, $tagNumber, $_SESSION['user']));
		pg_free_result($result); 
	} else {
		echo "Couldn't update values for your pet at this time. Please try again later";
	}
	
	//this function will refresh the list of feeders 
	//by querying the DB and printing out the formatted HTML.
	//it is located in assets/php_functions/phpFunctions.php
	$data = populateFeeders();
	echo $data;

?>












