<?php
session_start();
$loggedIn = empty($_SESSION['user']) ? false : $_SESSION['user'];
?>

<!DOCTYPE html>

<!-- navbar -->
	<nav class="navbar navbar-default navbar-fixed-top">
		<div class='container'>
			<div class="navbar-header">
				<p class="navbarText brand navbar-text">Change Password</p>
			</div>
		</div>
	</nav>
	<br><br><br>
	
	<html>
	<head>
	<?php include_once 'loginFunctions.php'; ?>	

	</head>
	<body>
	<br><br><br>
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
		$salt = rand();
		$user = $_SESSION['user'];
		
	
			//echo $user;
		if(($newPass != $conPass) || ($_SESSION['password_hash']!= sha1($oldPass . $_SESSION['salt']))) 
		{
			$message = "Passwords must match!";
			echo $_SESSION['password_hash'];
		}
		/* else
		{
			//connects to the db
				$dbconn = dbconnect();				
				$updateQ = "Update $schema.authentication SET password_hash = $1, salt = $2 WHERE user_email = $3";		
				$updatePrep = pg_prepare($dbconn,"prep", $updateQ);
				
					echo $updateQ;
				/*
				if($updatePrep) 
				{
				
					echo "Udoka Anugwo";
					$changePass= sha1($newPass . $salt);
					//execute the query
					$prepResult = pg_execute($dbconn,"prep", array($changePass,$salt,$user));
			
					//free result in case we want to use it again
					pg_free_result($prepResult);	
				}
			
			   $subject = "Feedmation Password Change";
			   $message = "Hey, your password has been successfully changed. You will now be able to 
					login with your changed password. \n\n Your changed password : $newPass \n
					Once you log in with your new password, you will then be able to change the 
					password.\n\n
					- Feedmation";
			   $header = "From: info@feedmation.com \r\n";
			   $retval = mail($user, $subject, $message,$header);
			 
			  if($retval == true )  
			   {
					echo "PARTYNEXTDOOR2";
				 // header("Location: confirmation.php");
			   }
			   else
			   {
				  echo "Confirmation email could not be sent!.";
			   } 
			//this code will only execute if the entered user name does not already exist
		} */
	}
  ?>
