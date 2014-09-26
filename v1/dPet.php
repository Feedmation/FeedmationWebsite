<?php
	session_start();
	$loggedIn = empty($_SESSION['user']) ? false : $_SESSION['user'];
	if ($loggedIn == false) {
		header("Location: index.php");
		exit;
	}
?>

<!-- navbar -->
	<nav class="navbar-default navbar-fixed-top">
		<div class='container'>
			<div class="navbar-header pull-left">
				<p class="navbarText brand navbar-text"><?php echo "$_SESSION[fname]'s Feedmation Home"; ?></p>
			</div>
			<div class="navbar-header pull-right">
				<p class="navbar-text">
					<a href="changeP.php" class='btn btn-default pull-right'>Change Password</a>  
					<a href="logout.php" class="btn btn-default pull-right">Logout</a>
				</p>	
			</div>
		</div>
	</nav>
	<br><br><br>

<!DOCTYPE html>
<html>
<head>
<?php include_once 'loginFunctions.php'; ?>	


<br>
		<label for='feeder'>Select a Feeder:</label>
		<br>
		<select name = 'feeder' class="form-control">
		  <option value="" selected>----------------------------</option>
		  <option value="favMovie">What is your favorite movie? </option>
		  <option value="momMaidenName">What is your mother's maiden name? </option>
		  <option value="elementarySchool">What is the name of the elementary school you attended? </option>
		  <option value="dreamJob">What is your dream job? </option>
		</select>
		<br>
		<br>
		<label for='Pet'>Select a Pet:</label>
		<br>
		<select name = 'pet' class="form-control">
		  <option value="" selected>----------------------------</option>
		</select>
