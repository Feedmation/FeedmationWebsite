<?php
	session_start();
	$loggedIn = empty($_SESSION['user']) ? false : $_SESSION['user'];
	if ($loggedIn == false) {
		header("Location: index.php");
		exit;
	}
	
include_once 'loginFunctions.php';
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
	<input type='number' name='cost' class="form-control" step='0.01' pattern='[0-9]*' > 
	<br>
	<label for='weight'>Update the weight of the bag of food? (in pounds):</label>
	<input type='number' name='weight' class="form-control" step='0.01' pattern='[0-9]*'> 
	<br>
	<center><a href="home.php" data-inline='true' class='btn btn-default backButton marginRight'>Cancel Update</a> <button type='submit' id='updateSubmitBtn' class="btn btn-default marginLeft">Submit Feeder Update</button></center>
 
	</form>

</body>
</html>
