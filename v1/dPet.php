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
			//feeder value
			$selected_Fdr = $_POST['feederId'];
			
			
		$selectPets = "SELECT * FROM $GLOBALS[schema].rfid WHERE user_email = $1 and feeder_id = $2";
		
		$selectPetsPrep = pg_prepare($dbconn, "pets", $selectPets);
		
		if($selectPetsPrep) {
			$petsResult = pg_execute($dbconn, "pets", array($_SESSION['user'], $selected_Fdr);
		} else {
			echo "Could not sanitize user name. Try again later.";
		}
		
		if($petsResult) {
			$pets = '';
			$i = 0;
			while($row = pg_fetch_assoc($petsResult)) {
				$pets.= "	<option value='$row[tag_id]'>$row[pet_name]</option>  ";			
				$i++;
			}
			pg_free_result($petsResult);
			echo $pets;
			
		} else {
			echo "Could not query for Pets. Try refreshing the page";
		}	
		?>
		
<label for='Pet'>Select a Pet:</label>
		<br>
		<select name="petId" required="required" class="form-control" id="petId">		
		<?php $pets ?>
		</select>
		<br><br>
			<center><br><a href="index.php">Feedmation</a><br><br></center>

		
		
