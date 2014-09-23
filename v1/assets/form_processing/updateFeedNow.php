<?php
	session_start();
	$loggedIn = empty($_SESSION['user']) ? false : $_SESSION['user'];
	if ($loggedIn == false) {
		header("Location: index.php");
		exit;
	}
	
include_once '../../loginFunctions.php';


	$dbConn = dbconnect();
	$feederId = $_POST['feederId'];
	$cups = $_POST['cups'];
	
	//check if a feed now request is already pending
	$pendingFeedNowSelect = "SELECT * FROM $GLOBALS[schema].feeders WHERE feeder_id = $1 AND feed_now_set = TRUE";
	$pendingFeedNowPrep = pg_prepare($dbConn, "pending", $pendingFeedNowSelect);
	
	if($pendingFeedNowPrep) {
		
		$pendingFeedNowResult = pg_execute($dbConn, "pending", array($feederId));	
		
	} else {
		echo "Couldn't sanitize query, try again later.";	
	}
	
	//if true, no pending requests. Update DB to start the feed now request.
	if(pg_num_rows($pendingFeedNowResult) == 0) {
		
		$updateFeedNow = "UPDATE $GLOBALS[schema].feeders SET feed_now_set = TRUE, feed_now_amount = $1 WHERE feeder_id = $2";
		$updateFeedNowPrep = pg_prepare($dbConn, "updateFeedNow", $updateFeedNow);
		
		if($updateFeedNowPrep) {
			
			pg_execute($dbConn, "updateFeedNow", array($cups, $feederId));
			
		} else {
			echo "Couldn't process Feed Now request.\nTry again later.";
		}
		
		//show the default home screen, a success message will also be shown. 
		$data = populateFeeders();
		echo $data;	
		
	} else {
		echo "pendingRequest";
	}
		
?>
