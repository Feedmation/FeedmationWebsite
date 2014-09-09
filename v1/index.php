<?php
session_start();
$loggedIn = empty($_SESSION['user']) ? false : $_SESSION['user'];
	if ($loggedIn == true) {
		header("Location: home.php");
		exit;
	}
?>




<!DOCTYPE html>
<html>
<head>
<?php include_once 'loginFunctions.php'; ?>	

</head>
<body>
	<!-- navbar -->
	<nav class="navbar navbar-default navbar-fixed-top">
		<div class='container'>
			<div class="navbar-header">
				<p class="navbarText brand navbar-text">Feedmation</p>
			</div>
		</div>
	</nav>
	<br><br><br>
<!-- start of main content -->
<div class="container">
  
  	<p id="errorMessage">
		<?php 
		if(isset($_POST['login'])) {
			login();
		}	
		?>
	</p>
  	
  <div id="formWrapper">	
	 
	<center> 
    <form method="post" class="form-inline" role="form" action="index.php">
	 
        <label for="username">Username:</label>
        <input type="email" class="form-control" required="required" name="username">    
        <br><br>
        <label for="password">Password:</label>
        <input type='password' class="form-control" pattern=".{8,16}" title="Must be between 8 and 16 characters" required="required" name="password"> 
		<br><br>

			<button type="submit" class='btn btn-default' name='login' id='submitButton'>Log In</button>
	</form>		
			<br>
			<p>Or</p>
			<a href="registration.php" class='btn btn-default'>Sign up!</a>
        </center>

  </div>
  
</div>

</body>
</html>
