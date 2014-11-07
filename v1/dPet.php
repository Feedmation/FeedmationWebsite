<?php
	session_start();
	$loggedIn = empty($_SESSION['user']) ? false : $_SESSION['user'];
	if ($loggedIn == false) {
		header("Location: index.php");
		exit;
	}
	
	include_once 'loginFunctions.php';	
?>



<!DOCTYPE html>
<html>
<head>

<script>
	function loadPetsSelect(feederId) 
	{
		
		var e = document.getElementById("feederSelect");
		var feederId = e.options[e.selectedIndex].value;
		
		$.ajax({
			url:'dPetHandler.php',
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
					$("#petSelect").html(data);
				}
			}
		});
	}
</script>

<? 
		$dbconn = dbconnect();
	?>
	<form id="delete" name="delete" action="dPet.php" method="POST">
	<!--Feeder-->
	<label for='pet'>Select a Feeder:</label>
	<select class="form-control" id='feederSelect' name='feeder' onchange="loadPetsSelect();">
	<option value="">--Please Select a Feeder--</option>
		<?php
		$dbconn = dbconnect();
	
		$selectFeeders = "SELECT * FROM $GLOBALS[schema].feeders WHERE user_email = $1";
		
		$selectFeedersPrep = pg_prepare($dbconn, "feeders", $selectFeeders);
		
		if($selectFeedersPrep) {
			$feedersResult = pg_execute($dbconn, "feeders", array($_SESSION['user']));
		} else {
			echo "Could not sanitize user name. Try again later.";
		}
		
		if($feedersResult) {
			$feeders = '';
			$i = 0;
			while($row = pg_fetch_assoc($feedersResult)) {
				$feeders.= "
							<option value='$row[feeder_id]'>$row[feeder_name]</option>
						   ";			
						  
				$i++;
			}
			pg_free_result($feedersResult);
			echo $feeders;
			
		} else {
			echo "Could not query for Pet Feeders. Try refreshing the page";
		}
		
		?>
	</select>
	<br><br>

	<!--Pets-->
	<label for='pet'>Select a Pet:</label>
	<select class="form-control" id='petSelect' name='pet'>
			<label for='pet'>Select a Pet:</label>
	</select>
	<?
	if(isset($_POST['deletePet']))
	{
		$tagID = $_POST['pet'];
		$feederID = $_POST['feeder'];
		
		$dbConn = dbconnect();
		//delete the feeder from the rfid table
		$petDelete = "DELETE FROM $GLOBALS[schema].rfid WHERE feeder_id = $1 AND tag_id = $2";
		
		$petDeletePrep = pg_prepare($dbConn, "petDelete", $petDelete);
		
		if($petDeletePrep) { 
			$petDeleteResult = pg_execute($dbConn, "petDelete", array($feederID,$tagID));	
		} 
		else 
		{
			echo "<p>Couldn't delete your pet from the feeder. </p>";
		}

		//delete from stats table
		$statsTblDelete = "DELETE FROM $GLOBALS[schema].stats WHERE tag_id = $1";
		
		$statsDeletePrep = pg_prepare($dbConn, "deleteStatsTbl", $statsTblDelete);
		
		if($statsDeletePrep) { 
			$statsTblDeleteResult = pg_execute($dbConn, "deleteStatsTbl", array($tagID));	
		} else {
			echo "<p>Couldn't delete your pet's stats from the stats table</p>";
		}
	
		//once all the necessary data has been delete, repopulate the feeder list on home.php
		$data = populateFeeders();
		
		header('Location: index.php');
		
	}
	?>
	
	
	
	
	<br><br>
			<center><button type='submit' name='deletePet' class='btn btn-default'>Delete Pet From Feeder</button>
		</form>
		<br><br>
			<center><a href="home.php" data-inline='true' class='btn btn-default backButton'>Go back to Feedmation Home</a></center>
			
			
