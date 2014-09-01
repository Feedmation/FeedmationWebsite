<?php
	session_start();
	$loggedIn = empty($_SESSION['user']) ? false : $_SESSION['user'];
	if ($loggedIn == false) {
		header("Location: index.php");
		exit;
	}
	
include_once 'loginFunctions.php';

	$dbConn = dbconnect();
	$feederId = $_GET['feederId'];
	
	//delete the feeder from the feeder table
	$feederTblDelete = "DELETE FROM $GLOBALS[schema].feeders WHERE feeder_id = $1";
	
	$feederDeletePrep = pg_prepare($dbConn, "deleteFeederTbl", $feederTblDelete);
	
	if($feederDeletePrep) { 
		$feederTblDeleteResult = pg_execute($dbConn, "deleteFeederTbl", array($feederId));	
	} else {
		echo "<p>Couldn't delete from the feeder table</p>";
	}

	//delete from stats table
	$statsTblDelete = "DELETE FROM $GLOBALS[schema].stats WHERE feeder_id = $1";
	
	$statsDeletePrep = pg_prepare($dbConn, "deleteStatsTbl", $statsTblDelete);
	
	if($statsDeletePrep) { 
		$statsTblDeleteResult = pg_execute($dbConn, "deleteStatsTbl", array($feederId));	
	} else {
		echo "<p>Couldn't delete from the stats table</p>";
	}
	
	//once all the necessary data has been delete, repopulate the feeder list on home.php
	$data = populateFeeders();
	echo $data;
	
?>
