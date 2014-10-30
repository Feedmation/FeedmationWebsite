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
</script>	

</head>
<body>
	
	<form method='POST' id='updateFeederForm' action="editFeeder.php">
	<label for='feeder'>Select a Feeder :</label>
	<select class="form-control" id='feederSelect' name='feeder'>
		<?php populateFeedersSelectBox(); ?>
	</select>
	<br>
	
	<label for='newFeederName'>Update Name for your Feeder:</label>
	<input type='text' name='newFeederName' class="form-control" required='required'>  
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

	<?
	$dbConn = dbconnect();
			
	if(isset($_POST['update']))
	{
		$foodCost = $_POST['cost'];
		$foodWeight = $_POST['weight'];
		$feederName = $_POST['newFeederName'];
		$feederID = $_POST['feeder'];
	
		if(!empty($_POST['feederName']))
		{
			//update the feeder name in the feeder table
			$nameUpdate = "UPDATE $GLOBALS[schema].feeders SET feeder_name = $1 WHERE feeder_id = $2";
			$nameUpdatePrep = pg_prepare($dbConn, "nameUpdate", $nameUpdate);
			$nameResult = pg_execute($dbConn, "nameUpdate", array($feederName,$feederID));
		}
		if(!empty($_POST['cost']))
		{
			//update feeder's food cost
			$costUpdate = "UPDATE $GLOBALS[schema].feeders SET food_bag_cost = $1 WHERE feeder_id = $2";
			$costUpdatePrep = pg_prepare($dbConn, "costUpdate", $costUpdate);
			$costResult = pg_execute($dbConn, "costUpdate", array($foodCost,$feederID));
		}
		if(!empty($_POST['weight']))
		{
			//update food weight
			$weightUpdate = "UPDATE $GLOBAL[schema].feeders SET food_bag_weight = $1 WHERE feeder_id = $2";
			$weightUpdatePrep = pg_prepare($dbConn, "weightUpdate", $weightUpdate);
			$weightResult = pg_execute($dbConn, "weightUpdate", array($foodWeight,$feederID));
		}
	
		header('Location: index.php');
	}
	
	?>


