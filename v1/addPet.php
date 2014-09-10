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

	$('#addPetForm').on('submit', function (e) {

		e.preventDefault();
		var start1String = $('#startTime1').val();
		var start1Split = start1String.split(":");
		var start1Int = parseInt(start1Split[0]);
		
		var end1String = $('#endTime1').val();
		var end1Split = end1String.split(":");
		var end1Int = parseInt(end1Split[0]);
		
		var start2String = $('#startTime2').val();
		var start2Split = start2String.split(":");
		var start2Int = parseInt(start2Split[0]);
		
		var end2String = $('#endTime2').val();
		var end2Split = end2String.split(":");
		var end2Int = parseInt(end2Split[0]);

		if(start1Int === 12) {
			start1Int = 0;
		}
		
		if(start2Int === 12) {
			start2Int = 0;
		}
		
		if(end1Int === 12) {
			end1Int = 0;
		}
		
		if(end2Int === 12) {
			end2Int = 0;
		}
		
		if( (start1Int >= end1Int) || (start2Int >= end2Int) ) {
			window.scrollTo(0,0);
			$(".errorMessage").hide().html("The start time cannot be greater than or equal to the end time!").fadeIn('slow');
			return;
		} 
          
		$.ajax({
		url: 'assets/form_processing/insert_pet.php',
		type: "POST",
		dataType: 'text',
		data: $("#addPetForm").serialize(),
		success: function(data) {
			var error = 'petExists';
			if(data.match(error)) {
				window.scrollTo(0,0);
				$(".errorMessage").hide().html("That pet tag has already been registered.<br>Try typing it again.").fadeIn('slow');
			} else {
				$(".errorMessage").empty();
				$("#feeders").html(data);
				$("#buttonBar").show();
			}
		}
	});	
	});
	
	$('#startTime1').timepicker({'disableTouchKeyboard':true,
								'minTime':'00:00am',
								'maxTime':'11:00am',
								'step':60});
	$('#endTime1').timepicker({'disableTouchKeyboard':true,
								'minTime':'00:00am',
								'maxTime':'11:00am',
								'step':60});
	$('#startTime2').timepicker({'disableTouchKeyboard':true,
								'minTime':'12:00pm',
								'maxTime':'11:00pm',
								'step':60});
	$('#endTime2').timepicker({'disableTouchKeyboard':true,
								'minTime':'12:00pm',
								'maxTime':'11:00pm',
								'step':60});
	
</script>	

</head>
<body>
    
	<form method="POST" id="addPetForm">		  
		<label for="name">Name of your Pet:</label>
        <input type="text" required="required" class="form-control" name="name">    
        <br>
        <label for="number">Tag Number:</label>
        <input type="text" required="required" class="form-control" name="number"> 
        <br>
		<label for="feederId">Which Feeder will the new pet use?</label>
		<select name="feederId" required="required" class="form-control" id="feederId">
			<?php populateFeedersSelectBox(); ?>
		</select>
		<br><br>
		<label for='firstTimeSlot'>Select time slot for first eating window:</label>
		<div class='well' name='firstTimeSlot'>
			<label for="startTime1">Start Time:</label>
			<input type="text" required="required" class="form-control" name="startTime1" id="startTime1" placeholder="Pick a Time"><br> 
			<label for="endTime1">End Time:</label>
			<input type="text" required="required" class="form-control" name="endTime1" id="endTime1" placeholder="Pick a Time"> 
		</div>
		<label for='firstTimeSlot'>Select time slot for second eating window:</label>
		<div class='well' name='secondTimeSlot'>
			<label for="startTime2">Start Time:</label>
			<input type="text" required="required" class="form-control timepicker" name="startTime2" id="startTime2" placeholder="Pick a Time"><br> 
			<label for="endTime2">End Time:</label>
			<input type="text" required="required" class="form-control" name="endTime2" id="endTime2" placeholder="Pick a Time"> 
		</div>
        <br><br>         
        <center><a href="home.php" data-inline='true' class='btn btn-default backButton marginRight'>Cancel Submission</a> <button type='submit' id='addFeederSubmitBtn' class="btn btn-default marginLeft">Submit Pet Info</button></center>
    </form>

</body>
</html>
