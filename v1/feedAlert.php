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
			
				if(!$feederResult) 
				{
					exit;
				}
				while($feederResult==TRUE)
				{
					$feederRow= pg_fetch_assoc($feederResult);
					$feederID= $feederRow['feeder_id'];
					$feederName = $feeder['feeder_name'];
					$user= $feederRow['user_email'];
					$tagQ = "SELECT tag_id, pet_name FROM $GLOBALS[schema].rfid WHERE feeder_id = $1";
					$tagPrep = pg_prepare($dbconn, "tag", $tagQ);
					$tagResult = pg_execute($dbconn,"tag",array($feederID));
					
					while($tagResult==TRUE)
					{
						$row = pg_fetch_assoc($tagResult);
						
							$tag_id = $row['tag_id'];
							$petName = $row['pet_name'];
							
							$eventQ = "SELECT tag_id FROM $GLOBALS[schema].stats WHERE tag_id = $1";
							$eventPrep = pg_prepare($dbconn, "event",$eventQ);
							$eventResult = pg_execute($dbconn,"event",array($tag_id));
							
								//Have not eaten b/w two time slots
								if(pg_num_rows($eventResult) ==0)
								{
							
									//free result in case we want to use it again
									//pg_free_result($prepResult);	
									
									$subject = "Feedmation - Feeding Reminder";
									$message = "Hey your pet, $petName did not eat from your feeder $feederName . \n\n Please be sure to 
												check on and make sure your pet receives a meal for today. 
													.\n\n\n\n
									- Feedmation";
									$header = "From: info@feedmation.com \r\n";
									$retval = mail($user, $subject, $message,$header);
									echo"Email sent!!!!" . $petName $tag_id;
								}
								pg_free_result($eventResult);
					}
						pg_free_result($tagResult);
				}
				pg_free_result($feederResult);

  ?>