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
				//echo"Starting query here ";
				if(!$feederResult) 
				{
					echo "Did not run query";
				}
				else
				{
					while($feederRow= pg_fetch_assoc($feederResult))
					{
						$feederID= $feederRow['feeder_id'];
						$feederName = $feederRow['feeder_name'];
						$user= $feederRow['user_email'];
						$tagQ = "SELECT tag_id, pet_name FROM $GLOBALS[schema].rfid WHERE feeder_id = '$feederID'";
						//$tagPrep = pg_prepare($dbconn, "tag", $tagQ);
						$tagResult = pg_query($dbconn,$tagQ);
						//echo "Found feeder " .$feederID;
						while($row = pg_fetch_assoc($tagResult))
						{
							$tag_id = $row['tag_id'];
								$petName = $row['pet_name'];
								
								$currentDateTime = date("Y-m-d H:i:s");
								$nextday = strftime("%Y-%m-%d %H:%i:%s", strtotime("$currentDateTime +1 day"));
								
								$eventQ = "SELECT tag_id FROM $GLOBALS[schema].stats WHERE tag_id = '$tag_id' AND event_time >= $currentDateTime AND event_time < $currentDateTime";
								//$eventPrep = pg_prepare($dbconn,"event", $eventQ);
								$eventResult = pg_query($dbconn,$eventQ);
								
									//Have not eaten b/w two time slots
									if(pg_num_rows($eventResult) ==0)
									{
								
										//free result in case we want to use it again
										//pg_free_result($prepResult);	
										
										$subject = "Feedmation - Feeding Reminder";
										$message = "Hey your pet, $petName did not eat from your feeder $feederName . \n\n Please be sure to 
													check on $petName and make sure your pet receives a meal for today. 
														.\n\n\n\n
										- Feedmation";
										$header = "From: info@feedmation.com \r\n";
										$retval = mail($user, $subject, $message,$header);
										echo "Email sent!!!! ".$tag_id ."<br></br>";
									}  
									pg_free_result($eventResult);
									
									//	echo "tag result is true.";
						}
						pg_free_result($tagResult);
						
					}
				}
				pg_free_result($feederResult);
				pg_close($dbconn);

  ?>