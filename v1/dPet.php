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
<?php include_once 'phpFunctions.php'; ?>	



<script>
	function loadPetsSelect(feederId) 
	{
		
		var e = document.getElementById("feederSelect");
		var feederId = e.options[selectBox.selectedIndex].value;
		
		alert("test ");
		$.ajax({
			url: 'phpFunctions.php',
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

<br>
<? 
		$dbconn = dbconnect();
	?>
	<!--Feeder-->
	<label for='pet'>Select a Feeder:</label>
	<select class="form-control" id='feederSelect' name='feeder'>
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
							<option value='$row[feeder_id]' onselect = \"loadPetsSelect('$row[feeder_id]')\">$row[feeder_name]</option>
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
		<?php /*populatePetsSelectBox();

		*/
			?>
			
			<label for='pet'>Select a Pet:</label>
	<select class="form-control" id='petSelect' name='pet'>
		<?php 
			/**/	
		?>
	</select>
	<?
	if(isset($_POST['submit']))
	{
		$tagID = $_GET['tag_id'];
		
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
	
		//once all the necessary data has been delete, repopulate the feeder list on home.php
		$data = populateFeeders();
	}
	?>
	
	
	
	
	<br><br>
			<center><button type='submit' name='deletePet' class='btn btn-default'>Delete Pet From Feeder</button>
		<br><br>
			<center><a href="home.php" data-inline='true' class='btn btn-default backButton'>Go back to Feedmation Home</a></center>
			
			
