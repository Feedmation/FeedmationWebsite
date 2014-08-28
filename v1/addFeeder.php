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
	
	<form method='POST' id='addFeederForm'>
		<label for='feederId'>Enter Unique Pet Feeder ID:</label>
        <input type='text' required='required' id="feederID" class="form-control" name='feederId'>      
        <br>
        <label for='feederName'>Custom Name for your new Feeder:</label>
        <input type='text' name='feederName' class="form-control" required='required' placeholder='example: Kitchen Feeder'>  
        <br>
		<label for='cost'>How much did your current bag of food cost?:</label>
        <input type='number' name='cost' class="form-control" step='0.01' pattern='[0-9]*' placeholder='example: 4.50'> 
        <br>
		<label for='weight'>How much does the bag of food weigh? (in pounds):</label>
        <input type='number' name='weight' class="form-control" step='0.01' pattern='[0-9]*' placeholder='example: 5.50'> 
        <br>
        <center><a href="home.php" data-inline='true' class='btn btn-default backButton marginRight'>Cancel Submission</a> <button type='submit' id='addFeederSubmitBtn' class="btn btn-default marginLeft">Submit Feeder Info</button></center>
     
	</form>

</body>
</html>
