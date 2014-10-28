<?php
	session_start();
	$loggedIn = empty($_SESSION['user']) ? false : $_SESSION['user'];
	if ($loggedIn == false) {
		header("Location: index.php");
		exit;
	}
	
include_once 'loginFunctions.php';
?>


<html>
<head>
	
<script>
	$( document ).ready(function() {
		$("#buttonBar").hide();
		$(".errorMessage").empty();
	});
	
	 $('#addFeederForm').on('submit', function (e) {

		e.preventDefault();
          
		$.ajax({
		url: 'assets/form_processing/insert_feeder.php',
		type: "POST",
		dataType: 'text',
		data: $("#addFeederForm").serialize(),
		success: function(data) {
			var error = 'feederExists';
			if(data.match(error)) {
				window.scrollTo(0,0);
				$(".errorMessage").hide().empty().html("That pet feeder has already been registered.<br>Try typing it again.").fadeIn('slow');
			} else {
				$(".errorMessage").empty();
				$("#feeders").html(data);
				$("#buttonBar").show();
			}
		}
	});	
	});
	
	
	
	<?
	$dbConn = dbconnect();
	
			//update the feeder in the feeder table
			$nameUpdate = "UPDATE FROM $GLOBALS[schema].feeders WHERE feeder_id = $1";
			$nameUpdatePrep = pg_prepare($dbConn, "deleteFeederTbl", $nameUpdate);
	
			$costUpdate = "UPDATE FROM $GLOBALS[schema].feeders WHERE feeder_id = $1";
			$costUpdatePrep = pg_prepare($dbConn, "deleteFeederTbl", $costUpdate);
			
			$weightUpdate = "UPDATE FROM $GLOBALS[schema].feeders WHERE feeder_id = $1";
			$weightUpdatePrep = pg_prepare($dbConn, "deleteFeederTbl", $weightUpdate);
			
	if(isset($_POST['update']))
	{
	
		//update the feeder in the feeder table
			$nameUpdate = "UPDATE FROM $GLOBALS[schema].feeders WHERE feeder_id = $1";
			$nameUpdatePrep = pg_prepare($dbConn, "nameUpdate", $nameUpdate);
	
			$costUpdate = "UPDATE FROM $GLOBALS[schema].feeders WHERE feeder_id = $1";
			$costUpdatePrep = pg_prepare($dbConn, "costUpdate", $costUpdate);
			
			$weightUpdate = "UPDATE FROM $GLOBALS[schema].feeders WHERE feeder_id = $1";
			$weightUpdatePrep = pg_prepare($dbConn, "weightUpdate", $weightUpdate);
			
		if(!empty($_POST['feederName']))
		{
			$nameResult = pg_execute($dbConn, "nameUpdate", array($tagID));
		}
		if(!empty($_POST['cost']))
		{
			
			$costResult = pg_execute($dbConn, "costUpdate", array($tagID));
		}
		if(!empty($_POST['weight']))
		{
			$weightResult = pg_execute($dbConn, "weightUpdate", array($tagID));
		}
	
	}
	
	?>

</script>	

</head>
<body>
	
	<form method='POST' id='updateFeederForm'>
	<label for='feeder'>Select a Feeder :</label>
	<select class="form-control" id='feederSelect' name='feeder'>
		<?php populateFeedersSelectBox(); ?>
	</select>
	<br>
	
	<label for='feederName'>Update Name for your Feeder:</label>
	<input type='text' name='feederName' class="form-control" required='required'>  
	<br>
	
	<label for='cost'>Update bag of food cost?:</label>
	<input type='number' name='cost' class="form-control" step='0.01' pattern='[0-9]*' placeholder='example: 4.50' > 
	<br>
	<label for='weight'>Update the weight of the bag of food? (in pounds):</label>
	<input type='number' name='weight' class="form-control" step='0.01' pattern='[0-9]*'placeholder='example: 5.50'> 
	<br>
	<center><a href="home.php" data-inline='true' class='btn btn-default backButton marginRight'>Cancel Update</a> <button type='submit' id='updateSubmitBtn' name = 'update' class="btn btn-default marginLeft">Submit Feeder Update</button></center>
 
	</form>

</body>
</html>
