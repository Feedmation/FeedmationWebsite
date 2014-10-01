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

<label for='Feeder'>Select a Feeder:</label>
		<br>
		<select name="feederId" required="required" class="form-control" id="feederId">
			<?php populateFeedersSelectBox(); ?>
		</select>
		<br><br>
		
		<?php 
			$selected_Fdr = $_POST['feederId'];
			echo  "You have selected :" .$selected_Fdr;
			?>
		<br><br>
			<center><br><a href="index.php">Feedmation</a><br><br></center>
		
		
