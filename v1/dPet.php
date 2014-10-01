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

<label for='Pet'>Select a Feeder:</label>
		<br>
		<select name="feederId" required="required" class="form-control" id="feederId">
			<?php populateFeedersSelectBox(); ?>
		</select>
		<br><br>
	<?php
		$user =  $_SESSION['user'];
		$dbConn = dbconnect();
		
		//delete the feeder from the feeder table
		$getPets = "SELECT feeder_name FROM $GLOBALS[schema].feeders WHERE user_email = $1";
			
		$getPetsPrep = pg_prepare($dbConn, "getPets", $grabPets);
	
	if($getPetsPrep) { 
		$getPetsResult = pg_execute($dbConn, "getPets", array($user));
		
		if($getPetsResult >0){
		?>
		<br>
		<label for='feeder'>Select a Feeder:</label>
		<br>
		<select name = 'feeder' class="form-control">
		  <option value="" selected>----------------------------</option>
		  
		  <?
		  while ($row = pg_fetch_assoc($getPetsResult)) 
		  {
            echo '<option value="'.htmlspecialchars($row['feeder_name']).'"></option>';
          }
		  ?>
		  <option value="favMovie">What is your favorite movie? </option>
		</select>
		
		<?php
		
		
		}
			
			
	} else {
		echo "<p>Couldn't delete your pet from the feeder table</p>";
	}
		
		
		?>
			<center><br><a href="index.php">Feedmation</a><br><br></center>

		
		
