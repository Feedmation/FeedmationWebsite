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
		$hash = $_SESSION['password_hash'];
		echo $hash;
	
	//echo $_SESSION['user'];
	if(isset($_POST['update']))
	{
		$oldPass = $_POST['password'];
		$newPass = $_POST['rePassword'];
		$conPass = $_POST['conPassword'];
		$oldSalt = $_SESSION['salt'];
		$user = $_SESSION['user'];
		
	
			//echo $user;
		if($newPass != $conPass)) 
		{
			$message = "Passwords must match!";
			
		}
	}
  ?>
