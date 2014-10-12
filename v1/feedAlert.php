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
				
				$feedQ = "SELECT tag_id,user_id,slot_one_start,slot_one_end, slot_two_start, slot_two_end 
							FROM $GLOBALS[schema].rfid";
				$feedPrep = pg_prepare($dbconn, "empty", $feedQ);
					//execute the query
					$feedResult = pg_execute($dbconn,"empty", array($user));
				
				if(!$feedResult) 
				{
					exit;
				}
				else
				{
					while ($row = pg_fetch_row($emptyResult))
					{
						$tag_id = $row[0];
						$user = row[1];
						$firstStartTime = row[2];
						$firstEndTime= row[3];
						$secondStartTime = row[4];
						$secondEndTime= row[5];
						
						//Have not eaten b/w two time slots
						if()
						{
					
							//free result in case we want to use it again
							//pg_free_result($prepResult);	
							
							$subject = "Feedmation - Feeding Reminder";
							$message = "Hey. $tag_id did not eat at all today. \n\n Please be sure to 
										check on $tag_id and make sure your pet receives a meal for today. 
											.\n\n\n\n
							- Feedmation";
							$header = "From: info@feedmation.com \r\n";
							$retval = mail($user, $subject, $message,$header);
						}
					}
  ?>