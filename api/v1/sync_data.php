<?php
include_once('objects/tag.php');
date_default_timezone_set('America/Chicago');

$feederid = null;
$func = null;


//set GET vars if not empty
if (!empty($_GET))
{
	$feederid = empty($_GET['feederid']) ? '' : $_GET['feederid'];
	$func = empty($_GET['function']) ? '' : $_GET['function'];
	$tag_id = empty($_GET['tagid']) ? '' : $_GET['tagid'];
}


//set POST vars if not empty
if (!empty($_POST))
{
	$feederid = empty($_POST['feederid']) ? '' : $_POST['feederid'];
	$func = empty($_POST['function']) ? '' : $_POST['function'];
	$tag_id = empty($_POST['tagid']) ? '' : $_POST['tagid'];
	$amount = empty($_POST['amount']) ? NULL : $_POST['amount'];
	$amount = ($amount \ 100.00); //convert to cups
	$eatenWeight = empty($_POST['eatenWeight']) ? NULL : $_POST['eatenWeight'];
	$eatenWeight = ($eatenWeight * .002205); // covert grams to lbs.
	$time = empty($_POST['time']) ? NULL : $_POST['time'];
}

//variable for the schema used in the database.
//store it here so we can easily change it if need be. 
//to access it from ANY scope of ANY file use:
//$GLOBALS['schema']
//if already inside of double quotes then just use:
//$GLOBALS[schema]

$schema = "feedmati_system";

//connects to the db
$dbConnString = "host=173.254.28.90 options='--client_encoding=UTF8' user=feedmati_user dbname=feedmati_system password=PZi0wuz9n+XX";
$dbConn = pg_connect($dbConnString ) or die("Problem with connection to PostgreSQL:".pg_last_error());	

//Start query to check if feeder auth is valid
$authQuery = "SELECT * FROM $GLOBALS[schema].feeders WHERE feeder_id = $1";
$stmt = pg_prepare($dbConn,"auth",$authQuery);
	
echo pg_result_error($stmt);

//if statement won't prepare then return error else execute statment
if(!$stmt)
{
	header('Content-Type: application/json');
	echo json_encode(array("error" => "database query error"));
	return;

} else {

	$result =  pg_execute($dbConn,"auth",array($feederid));

	//if one feeder id match was found then run requested function.
	if(pg_num_rows($result)==1)
	{
	
		switch($func)
		{
			//data loging function
			case 'log_data':
				
				//Start insert
				
				$logInsert = "INSERT INTO $GLOBALS[schema].stats (tag_id, feeder_id, amtfedcups, amtatecups, amtateweight, petweight, event_time) VALUES ($1, $2, $3, $4, $5, $6, $7)";

				$logPrep = pg_prepare($dbConn, "insertLog", $logInsert);

				if($logPrep) {
					
					pg_execute($dbConn, "insertLog", array($tag_id, $feederid, $amount, NULL, $eatenWeight, NULL, $time));	
					
				
					header('Content-Type: application/json');
					echo json_encode(array("logData" => "Submited"));
				
				} else {
					header('Content-Type: application/json');
					echo json_encode(array("logdata" => "failed"));
				}
				
			break;
		
			// function for providing per feeder with tag settings
			case 'pull_settings':
		
				//Start query to get tag settings for a feeder
				$tagQuery = "SELECT * FROM $GLOBALS[schema].rfid WHERE feeder_id = $1 ORDER BY tag_slot ASC";
				$stmt = pg_prepare($dbConn,"tags",$tagQuery);

				//if statement won't prepare then return error else execute statment
				if(!$stmt) {
			
					header('Content-Type: application/json');
					echo json_encode(array("error" => "database query error"));
					return;

				} else {

					$tagResults =  pg_execute($dbConn,"tags",array($feederid));
					
					if(pg_num_rows($tagResults) >= 0) {
				
						$tagArray = array();
						$tagArray[1] = null;
						$tagArray[2] = null;
						$tagArray[3] = null;
						$tagArray[4] = null;

						while($tagRow = pg_fetch_assoc($tagResults)) {
		
							$tagChange = $tagRow['has_changed'];
							$tagID = $tagRow['tag_id'];
							$amount = $tagRow['feed_amount'];
							$slot1Start = $tagRow['slot_one_start'];
							$slot1End = $tagRow['slot_one_end'];
							$slot2Start = $tagRow['slot_two_start'];
							$slot2End = $tagRow['slot_two_end'];
							$slotNum = $tagRow['tag_slot'];
					
							//Boolean fix
							if ( $tagChange == 'f' ) {
								$tagChange = false;
							} else {
								$tagChange = true;
							}
					
							$tag = new Tag($tagChange, $tagID, $amount, $slot1Start, $slot1End, $slot2Start, $slot2End);
							$tagArray[$slotNum] = $tag->getArray();
						}
				
						header('Content-Type: application/json');
						echo json_encode($tagArray);
					}
				
					pg_free_result($tagResults);
				}
				
				//Start query to update last synced time stamp
				$now = date("Y-m-d H:i:s");
				$lastSynced = "UPDATE $GLOBALS[schema].feeders SET last_synced = '$now' WHERE feeder_id = $1";
				$stmt = pg_prepare($dbConn,"lastSynced",$lastSynced);

				if($stmt) {
			
					pg_execute($dbConn,"lastSynced",array($feederid));

				}
			
			break;
  
			// function for providing pet feeder with feed now request info
			case 'feed_now':
		
				//Start query to get feeder feed now data
				$feederQuery = "SELECT * FROM $GLOBALS[schema].feeders WHERE feeder_id = $1";
				$stmt = pg_prepare($dbConn,"feednow",$feederQuery);

				//if statement won't prepare then return error else execute statment
				if(!$stmt) {
			
					header('Content-Type: application/json');
					echo json_encode(array("error" => "database query error"));
					return;

				} else {

					$feederResults =  pg_execute($dbConn,"feednow",array($feederid));
					if(pg_num_rows($feederResults)==0) {
				
						header('Content-Type: application/json');
						echo json_encode(array("error" => "no feed now data available for given feeder id"));
						
					} else {
					
						if(pg_num_rows($feederResults) == 1) {
						
								$feederData = pg_fetch_assoc($feederResults);
								$feedNowSet = $feederData['feed_now_set'];
								$feedNowAmount = $feederData['feed_now_amount'];
							
								//Boolean fix
								if ( $feedNowSet == 'f' ) {
									$feedNowSet = false;
								} else {
									$feedNowSet = true;
								}
							
								$feedNowData = array('feedNow' => $feedNowSet,'feedAmount' => $feedNowAmount);
							
								header('Content-Type: application/json');
								echo json_encode($feedNowData);
						}
					}
				
					pg_free_result($feederResults);
				}
			
			break;
		
			//function for receiving tag update response back from the micro controller
			case 'tag_complete':
		
				//Start query to process tag update notification 
				$updateTagQuery = "UPDATE $GLOBALS[schema].rfid SET has_changed = false WHERE feeder_id = $1 AND tag_id = $2";
				$stmt = pg_prepare($dbConn,"updatetag",$updateTagQuery);

				if($stmt) {
			
					pg_execute($dbConn,"updatetag",array($feederid, $tag_id));

				}
			
			break;
		
			//function for receiving tag update response back from the micro controller
			case 'feednow_complete':
		
				//Start query to process that feednow has completed 
				$feednowComplete = "UPDATE $GLOBALS[schema].feeders SET feed_now_set = false WHERE feeder_id = $1";
				$stmt = pg_prepare($dbConn,"feednowComplete",$feednowComplete);

				if($stmt) {
			
					pg_execute($dbConn,"feednowComplete",array($feederid));

				}
			
			break;
			
			//function for receiving when food tank is empty from the micro controller
			case 'tank_empty':
		
				//Start query
				$foodTankEmpty = "UPDATE $GLOBALS[schema].feeders SET empty = true WHERE feeder_id = $1";
				$stmt = pg_prepare($dbConn,"foodTankEmpty",$foodTankEmpty);

				if($stmt) {
			
					pg_execute($dbConn,"foodTankEmpty",array($feederid));

				}
				
			    //Start query to get email and feeder info
				$emailQuery = "SELECT * FROM $GLOBALS[schema].feeders WHERE feeder_id = $1";
				$stmt = pg_prepare($dbConn,"email",$emailQuery);

				//if statement prepares then execute statement
				if($stmt) {
					$feederResults =  pg_execute($dbConn,"email",array($feederid));
					//if a match is found, then get info and send email
					if(pg_num_rows($feederResults)==1) 
					{
						$feederData = pg_fetch_assoc($feederResults);
						$userEmail = $feederData['user_email'];
						$feederName = $feederData['feeder_name'];
						
						$to = $userEmail;
						$fName = "Info Feedmation";
						$femail = "info@feedmation.com";
						$subject = "Feedmation Alert: Empty Feeder";
						$message = "Hey, $feederName feeder is out of food.\n\n\n- Feedmation Alerts";

						$headers = "From: \"".$fName."\" <".$femail.">\n"; 
						$headers .= "Return-Path: <".$femail.">\n"; 

						mail($to, $subject, $message, $headers);
						
					}
					pg_free_result($feederResults);
				}
			
			break;
			
			//function for receiving when food tank is full from the micro controller
			case 'tank_full':
		
				//Start query
				$foodTankFull = "UPDATE $GLOBALS[schema].feeders SET empty = false WHERE feeder_id = $1";
				$stmt = pg_prepare($dbConn,"foodTankFull",$foodTankFull);

				if($stmt) {
			
					pg_execute($dbConn,"foodTankFull",array($feederid));

				}
			
			break;

			default:
				header("HTTP/1.0 405 Method Not Allowed");
				echo json_encode(array("error" => "function not allowed"));
			break;
		}
		return;
		
	} else {
		echo json_encode(array("error" => "invalid feeder id"));
		return;
	}
}

pg_close($dbConn);
?>