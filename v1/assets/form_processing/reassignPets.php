<?php
	session_start();
	$loggedIn = empty($_SESSION['user']) ? false : $_SESSION['user'];
	if ($loggedIn == false) {
		header("Location: index.php");
		exit;
	}
	
include_once '../../loginFunctions.php';

	$dbconn = dbconnect();
	$tagIds = $_POST['reassignTags'];
	$feeders = $_POST['feeders'];
	
	//create all queries and prepared statements that will be used for each pet
	$updateRfidTable = "UPDATE $GLOBALS[schema].rfid SET feeder_id = $1 WHERE tag_id = $2 AND user_email = $3";
	$updateRfidTablePrep = pg_prepare($dbconn, "updateRfid", $updateRfidTable);
	
	$i = 0;
	//reassign all pets to the requested feeders
	foreach($tagIds as $tagId) {
		
		if($updateRfidTablePrep) {
			$updateRfidTableResult = pg_execute($dbconn, "updateRfid", array($feeders[$i], $tagId, $_SESSION['user']));
		} else {
			echo "Could not reassign feeders. Try again later.";
		}
		
		pg_free_result($updateRfidTableResult);
		$i++;
	}























?>

