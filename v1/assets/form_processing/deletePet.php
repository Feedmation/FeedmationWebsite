<?php
	session_start();
	$loggedIn = empty($_SESSION['user']) ? false : $_SESSION['user'];
	if ($loggedIn == false) {
		header("Location: index.php");
		exit;
	}
	
include_once '../../loginFunctions.php';

	$dbconn = dbconnect();
	
	$tagIds = $_POST['tags'];
	
	//create query and prepared statements that will be used multiple times
	$deleteStats = "DELETE FROM $GLOBALS[schema].stats WHERE tag_id = $1 AND user_email = $2";
	$deleteStatsPrep = pg_prepare($dbconn, "deleteStats", $deleteStats);
	
	$deleteRfid = "DELETE FROM $GLOBALS[schema].rfid WHERE tag_id = $1 AND user_email = $2";
	$deleteRfidPrep = pg_prepare($dbconn, "deleteRfid", $deleteRfid);
		
	
	//delete all necessary data for each tagId
	foreach($tagIds as $deleteId) {
		
		//delete from stats table first
		if($deleteStatsPrep) {
			$deleteStatsResult = pg_execute($dbconn, "deleteStats", array($deleteId, $_SESSION['user']));
		} else {
			echo "Could not sanitize user email. Try again later.";
		}
		
		//delete from rfid table		
		if($deleteRfidPrep) {
			$deleteRfidResult = pg_execute($dbconn, "deleteRfid", array($deleteId, $_SESSION['user']));
		} else {
			echo "Could not sanitize user email. Try again later.";
		}
		
		pg_free_result($deleteStatsResult);
		pg_free_result($deleteRfidResult);
	}


?>

