<?php
/* 	session_start();
	$loggedIn = empty+($_SESSION['user']) ? false : $_SESSION['user'];
	if ($loggedIn == false) {
		header("Location: index.php");
		exit;
	} */
	
include_once 'loginFunctions.php';
?>

<html>
<head>

<?php
				//connects to the db
				$dbconn = dbconnect();
				
				$feederQ =  "SELECT feeder_id, feeder_name,user_email FROM $GLOBALS[schema].feeders";
				$feederPrep = pg_prepare($dbconn, "empty", $feederQ);
				//execute the query
				$feederResult = pg_execute($dbconn,"empty",array());
				echo"Starting query here ";
				if(!$feederResult) 
				{
					echo "Did not run query";
				}
				else
				{
					while($feederResult==TRUE && $feederRow= pg_fetch_assoc($feederResult))
					{
						$feederID= $feederRow['feeder_id'];
						$feederName = $feederRow['feeder_name'];
						$user= $feederRow['user_email'];
						$tagQ = "SELECT tag_id, pet_name FROM $GLOBALS[schema].rfid WHERE feeder_id = $1";
						$tagPrep = pg_prepare($dbconn, "tag", $tagQ);
						$tagResult = pg_execute($dbconn,"tag",array($feederID));
						echo "Found feeder " .$feederID;
						while($tagResult==TRUE && $row = pg_fetch_assoc($tagResult))
						{
						
						echo "tag result is true.";
						
						}
						pg_free_result($tagResult);
					}
				}
				pg_free_result($feederResult);

  ?>