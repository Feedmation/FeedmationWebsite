
<!DOCTYPE html>

<!-- navbar -->
	<nav class="navbar navbar-default navbar-fixed-top">
		<div class='container'>
			<div class="navbar-header">
				<p class="navbarText brand navbar-text">Reset Password</p>
			</div>
		</div>
	</nav>
	<br><br><br>

<html>
<head>
<?php include_once 'loginFunctions.php'; ?>	

</head>
<body>

<div data-role="main" class="container">
    <form method="post" action="reset.php">
	<br><br><br>
		<label for='email'>Email Address:</label>
		<input type='email' class="form-control" name='email' required="required" id='email'>
		<br><br>
		<label for='secQues'>Security Question:</label>
		<br>
		<select name = 'secQues' class="form-control">
		  <option value="" selected>------------------------------------------------</option>
		  <option value="favMovie">What is your favorite movie? </option>
		  <option value="momMaidenName">What is your mother's maiden name? </option>
		  <option value="elementarySchool">What is the name of the elementary school you attended? </option>
		  <option value="dreamJob">What is your dream job? </option>
		</select>
		<br><br>
		<label for='secAns'>Security Answer:</label>
		<input type='text' class="form-control" required="required" name='secAns' id='secAns'> 
		<br>

		<br>
		<center><button type='submit' name='reset' class='btn btn-default'>Reset Password</button>
		<br>

<?php

//random password generator
	function randomPassword() 
	{
		$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < 8; $i++) 
		{
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return implode($pass); //turn the array into a string
	}
		
		$user = $_POST['email'];
		$secQues = $_POST['secQues'];
		$secAns = $_POST['secAns'];
        
		//$se = "SELECT user_email,secques,secans FROM $schema.authentication WHERE user_email= $1 AND secques = $2 AND secans = $3";
		//echo $se;
	
	if(isset($_POST['reset']))
	{	
		//connects to the db
		$dbconn = dbconnect();
		
		//query
        $securityQuery = "SELECT user_email,secques,secans FROM $schema.authentication WHERE user_email= $1 AND secques = $2 AND secans = $3";
				 
		//prepare a statement for the query
		$searchSec= pg_prepare($dbconn, "search", $securityQuery);
		
		if($searchSec) {
			//execute the query
			$secResult = pg_execute($dbconn, "search", array($user,$secQues,$secAns));
			
			//this will return the number of rows, if any, that were returned by the query. 0 means the username does not exist, 1 means it already exists. 
			$found = pg_num_rows($secResult);
			
			//free result in case we want to use it again
			pg_free_result($secResult);
		}
		else
		{
			echo "Failed!";
		}
	
        if($found>=1)
        {	 
			 //connects to the db
			$dbconn = dbconnect();

			$updateQ = "Update $schema.authentication SET password_hash = $1, salt = $2 WHERE user_email = $3";
						
			$updatePrep = pg_prepare($dbconn, "prep", $updateQ);
			
			if($updatePrep) 
			{
				$pass = randomPassword();
				$salt = rand();
				$encrypt = sha1($pass . $salt);

				//execute the query
				$prepResult = pg_execute($dbconn, "prep", array($encrypt,$salt,$user));
				
				//this will return the number of rows, if any, that were returned by the query. 
				//0 means the username does not exist, 1 means it already exists. 
				$found = pg_num_rows($prepResult);
				
				//free result in case we want to use it again
				pg_free_result($prepResult);	
			} 	
			 
 
			 
			   //$to = "udoka";
			   $subject = "Feedmation Password Reset";
			   $message = "Hey, your password has been successfully reset. You will now be able to 
					login with your new password. \n\n Your new password : $pass \n\n
					Once you log in with your new password, you will then be able to change the 
					password.\n\n\n\n
					- Feedmation";
			   $header = "From: info@feedmation.com \r\n";
			   $retval = mail($user, $subject, $message,$header);
			
			  
			  if( $retval == true )  
			   {
				  header("Location: confirmation.php");
			   }
			   else
			   {
				  echo "Message could not be sent! Incorrect security question and/or answer.";
			   }
							 
				//header("Location: http://babbage.cs.missouri.edu/~uafy6/Feedmation/FeedmationWebsite/v1/confirmation.php");
		}
		else
		{
			$message = "Account not found. Please signup now!!";
			echo $message;
		}			
		//header("Location: http://babbage.cs.missouri.edu/~uafy6/Feedmation/FeedmationWebsite/v1/confirmation.php");
	}
?>
