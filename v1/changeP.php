<?php
	session_start();
	$loggedIn = empty($_SESSION['user']) ? false : $_SESSION['user'];
	if ($loggedIn == false) {
		header("Location: index.php");
		exit;
	}
	
include_once 'loginFunctions.php';
?>

<!DOCTYPE html>
	
<html>
<head>

</head>
<body>
<div data-role="main" class="container">
<form method="post" action="changeP.php">
	<label for='password'>Password:</label>
	<input type='password' class="form-control" name='password' pattern=".{8,16}" title="Must be between 8 and 16 characters" required="required" id='password'>
	<br>
	<label for='repassword'>Enter a new Password:</label>
	<input type='password' class="form-control" pattern=".{8,16}"  name='rePassword' title="Must be between 8 and 16 characters" required="required" id='repassword'>
	<br>
	<label for='rePassword'>Re-Enter your new Password:</label>
	<input type='password' class="form-control" pattern=".{8,16}"  name='conPassword' title="Must be between 8 and 16 characters" required="required" id='conPassword'>
	<br><br>
	<center><button type='submit' name='update' class='btn btn-default'>Change Password</button>
	<br><br>
				<center><a href="home.php" data-inline='true' class='btn btn-default backButton'>Go back to Feedmation Home</a></center>
			<br>
</form>
</div>
 
  <?php
		
	
	//echo $_SESSION['user'];
	if(isset($_POST['update']))
	{
		$oldPass = $_POST['password'];
		$newPass = $_POST['rePassword'];
		$conPass = $_POST['conPassword'];
	
		//$oldSalt = $_SESSION['salt'];
		$user = $_SESSION['user'];
	
			//echo $user;
		if($newPass != $conPass)
		{
			$message = "Passwords must match!";
		}
		else
		{	
				//connects to the db
				$dbconn = dbconnect();
				
				$updateQ = "Update $schema.authentication SET password_hash = $1, salt = $2 WHERE user_email = $3";
				//echo $updateQ;
				
				$updatePrep = pg_prepare($dbconn, "prep", $updateQ);
			
				if($updatePrep == true) 
				{
					//header("Location: home.php");
					$salt = rand();
					$changePass = sha1($newPass . $salt);
					
					//execute the query
					$prepResult = pg_execute($dbconn,"prep", array($changePass,$salt,$user));
			
					//free result in case we want to use it again
					pg_free_result($prepResult);	
					
					$subject = "Feedmation Password Reset";
					$message = "Hey, your password has been successfully reset. You will now be able to 
					login with your new password. \n\n Your new password : $conPass \n\n
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
					
					
					
					
				}
		
		}
	}
  ?>
