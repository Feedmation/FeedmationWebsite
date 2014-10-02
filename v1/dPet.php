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



<script>

	function loadPetsSelect(feederId) {
		$.ajax({
			url: 'dPet.php',
			type: "GET",
			data: {	populatePetsSelect: 'true',
					feederId : feederId},
			success: function(data) {
				var error = 'error';
				if(data.match(error)) {
					window.scrollTo(0,0);
					$(".errorMessage").hide().html("There was an error populating dropdown for pets. Try again later.").fadeIn('slow');
				} else {
					$(".errorMessage").empty();
					$("#statsTable").html(data);
				}
			}
		});
	}

$(document).ready(function() {	
$('select').change(function() '
{
			
			var e = document.getElementById("feederId");
			var feederId = e.options[selectBox.selectedIndex].value;
});
		});

</script>


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
		} else 
		{
			echo "Could not query for Pet Feeders. Try refreshing the page";
		}
		$selected_Fdr = $_POST['feederId'];
		
		//echo "Your selected value is " .$selected_Fdr;
	
	
	//Pets
		$selectPets = "SELECT * FROM $GLOBALS[schema].rfid WHERE user_email = $1 and feeder_id = $2";
		
		$selectPetsPrep = pg_prepare($dbconn, "pets", $selectPets);
		
		if($selectPetsPrep) {
			$petsResult = pg_execute($dbconn, "pets", array($_SESSION['user'], $_GET['feederId']));
		} else {
			echo "Could not sanitize user name. Try again later.";
		}
		if($petsResult) 
		{
			$pets = '';
			$i = 0;
			while($row = pg_fetch_assoc($petsResult)) 
		{
				$pets.= "<option value='$row[tag_id]'>$row[pet_name]</option>";

?>
		<label for='Pets'>Select a Pet:</label>
		<br>
		<select name="petId" required="required" class="form-control" id='petSelect'>
		<div data-role="main" id="main-content" class="ui-content">
		<?echo $pets ?>
		</div>
		</select>
		<br><br>
<?							
				$i++;
		}
			}
			else {
			echo "Could not query for Pets. Try refreshing the page";
		}

	//tagID had to be value
	$tagID = $_GET['tag_id'];
	
	
	if($tagID>0)
	{
	//delete the feeder from the feeder table
	$petDelete = "DELETE FROM $GLOBALS[schema].feeders WHERE tag_id = $1";
	
	$petDeletePrep = pg_prepare($dbConn, "petDelete", $petDelete);
	
	if($petDeletePrep) { 
		$petDeleteResult = pg_execute($dbConn, "petDelete", array($petID));	
	} 
	else 
	{
		echo "<p>Couldn't delete your pet from the feeder. </p>";
	}

		//delete from stats table
		$statsTblDelete = "DELETE FROM $GLOBALS[schema].stats WHERE tag_id = $1";
		
		$statsDeletePrep = pg_prepare($dbConn, "deleteStatsTbl", $statsTblDelete);
		
		if($statsDeletePrep) { 
			$statsTblDeleteResult = pg_execute($dbConn, "deleteStatsTbl", array($feederId));	
		} else {
			echo "<p>Couldn't delete your pet's stats from the stats table</p>";
		}
	
	}
	
	//once all the necessary data has been delete, repopulate the feeder list on home.php
//	$data = populateFeeders();
//	echo $data;

		
		?>
		<br><br>
			<center><a href="home.php" data-inline='true' class='btn btn-default backButton'>Go back to Feedmation Home</a></center>

		
		
