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
				
				$emptyQ = "SELECT empty, feeder_name, user_email FROM $GLOBALS[schema].feeders";
				$emptyPrep = pg_prepare($dbconn, "empty", $emptyQ);
					//execute the query
					$emptyResult = pg_execute($dbconn,"empty", array($user));
				
				if(!$emptyResult) 
				{
					exit;
				}
				else
				{
					while ($row = pg_fetch_row($emptyResult))
					{
						$empty = $row[0];
						$feederName = row[1];
						$user = row[2];
						if($empty == TRUE)
						{
					
							//free result in case we want to use it again
							//pg_free_result($prepResult);	
							
							$subject = "Feedmation - Empty Feeder";
							$message = "Hey, your password has been successfully reset. You will now be able to 
							login with your new password. \n\n Your new password : $conPass \n\n
							Once you log in with your new password, you will then be able to change the 
							password.\n\n\n\n
							- Feedmation";
							$header = "From: info@feedmation.com \r\n";
							$retval = mail($user, $subject, $message,$header);
						}
					}
  ?>
