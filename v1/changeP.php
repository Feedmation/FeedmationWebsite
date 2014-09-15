<?php
session_start();
$loggedIn = empty($_SESSION['user']) ? false : $_SESSION['user'];
?>

<!DOCTYPE html>

<!-- navbar -->
	<nav class="navbar navbar-default navbar-fixed-top">
		<div class='container'>
			<div class="navbar-header">
				<p class="navbarText brand navbar-text">New Password</p>
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
    <form method="post" action="registration.php">
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
	$resetPass = $_POST['password'];
	$newPass = $_POST['rePassword'];
	$conPass = $_POST['conPassword'];
	
	if(isset($_POST['update']))
	{
		if($newPass != $conPass) 
		{
		$message = "Passwords must match!";
		}
		//that is the last input that must be verified.
		//from here, continue with adding everything to the DB 
		//and redirecting the user. 
		else 
		{
			//connects to the db
			$dbconn = dbconnect();

			$updateQ = "Update $schema.authentication SET password_hash = $1 WHERE user_email = $2 
						AND password_hash = $3";
						
			$updatePrep = pg_prepare($dbconn, "prep", $updateQ);
			
			if($updatePrep) 
			{
				//execute the query
				$prepResult = pg_execute($dbconn, "prep", array($newPass,$user,$resetPass));
			
				//free result in case we want to use it again
				pg_free_result($prepResult);	
			} 	
			else 
			{
				$message = "failed";
			}	
		
			if($prepResult==true) 
			{
				$message = "Your password has been successfully changed."; 
			}
			//this code will only execute if the entered user name does not already exist
			else 
			{
				$message = "Error!! Your password has not changed!";
			}
			
		}
	}
  ?>
