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
<? 

		$dbconn = dbconnect();
	
		$selectFeeders = "SELECT * FROM $GLOBALS[schema].feeders WHERE user_email = $1";
		
		$selectFeedersPrep = pg_prepare($dbconn, "feeders", $selectFeeders);
		
		if($selectFeedersPrep) {
			$feedersResult = pg_execute($dbconn, "feeders", array($_SESSION['user']));
		} else {
			echo "Could not sanitize user name. Try again later.";
		}
		
		if($feedersResult) 
		{
			$feeders = '';
			$i = 0;
			while($row = pg_fetch_assoc($feedersResult)) 
			{
				$feeders.= "
							<option value='$row[feeder_id]' selected>$row[feeder_name]</option>";	
?>
<label for='Feeder'>Select a Feeder:</label>
		<br>
		<select name="feederId" required="required" class="form-control" id="feederId">
		<?echo $feeders ?>
				</select>
		<br><br>
		<?				   
				$i++;
			}
			pg_free_result($feedersResult);
		///	echo $feeders;
			
		} else 
		{
			echo "Could not query for Pet Feeders. Try refreshing the page";
		}
		$selected_Fdr = $_POST['feederId'];
		
		echo "Your selected value is " .$selected_Fdr;
?>
		<br><br>
			<center><br><a href="index.php">Feedmation</a><br><br></center>
		
		
