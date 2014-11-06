<?php
	session_start();
	$loggedIn = empty($_SESSION['user']) ? false : $_SESSION['user'];
	if ($loggedIn == false) {
		header("Location: index.php");
		exit;
	}
?>



<!DOCTYPE html>
<html>
<head>

<!-- navbar -->
	<nav id='navbar' role="navigation" class="navbar navbar-default navbar-fixed-top">
		<div class='container'>
			<div class="navbar-header">
				<p class="navbarText pull-left navbar-text"><?php echo "$_SESSION[fname]'s Feedmation Home"; ?></p>
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<div id="navbarCollapse" class="collapse navbar-collapse">
				<ul id='navList' class='nav navbar-nav pull-right'>					
					<li><a href='#' id='feedNow' name='feedNow' onclick='feedNow(); return false;'><span class='glyphicon glyphicon-time'></span> Feed Now!</a></li>
					<li><a href='#' name='addFeeder' onclick="addFeeder(); return false;"><span class='glyphicon glyphicon-plus'></span> Add Feeder</a></li>
					<li><a href='#' name='editFeeder' onclick="editFeeder(); return false;"><span class='glyphicon glyphicon-cog'></span> Edit Feeder</a></li>
					<li><a href='#' name='deleteFeeder' onclick="deleteFeeder(); return false;" ><span class='glyphicon glyphicon-trash'></span> Delete Feeder</a></li>
					<li><a href='#' name='addPet' onclick="addPet(); return false;"><span class='glyphicon glyphicon-plus'></span> Add Pet</a></li>
					<li><a href='#' name='addPet' onclick="editPet(); return false;"><span class='glyphicon glyphicon-cog'></span> Edit Pet</a></li>
					<li><a href='dPet.php' id="deletePetBtn" name='deletePet'><span class='glyphicon glyphicon-trash'></span> Delete Pet</a></li>
					<li class="divider"></li>
					<li><a href="changeP.php"><span class='glyphicon glyphicon-user'></span> Change Password</a></li>
					<li><a href="logout.php"><span class='glyphicon glyphicon-off'></span> Logout</a></li>				
				</ul>
			</div>
		</div>	
	</nav>
	<!-- end navbar -->


<?php include_once 'loginFunctions.php'; ?>	
<?php include_once './assets/php_functions/phpFunctions.php'; ?>	
	
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>


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

<br>
<? 
		$dbconn = dbconnect();
	?>
	<br><br><br>
	<form id="delete" name="delete" action="dPet.php" method="POST">
	<br><br><br>
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
			
			
