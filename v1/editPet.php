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
	function updatePet() {

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
		url: 'assets/form_processing/update_pet.php',
		type: "POST",
		dataType: 'text',
		data: $("#updatePetForm").serialize(),
		success: function(data) {	
			$(".errorMessage").empty();
			$("#feeders").html(data);
		}
	});	
	}
								
	$(document).ready(function() {	
		$('.errorMessage').empty();
		
		var selectBox = document.getElementById("petSelect");
		var tagId = selectBox.options[selectBox.selectedIndex].value;
		loadEditForm(tagId);
		
		$('select').change(function() {
			tagId = selectBox.options[selectBox.selectedIndex].value;
			loadEditForm(tagId);
		});
	});
	
	function loadEditForm(tagId) {
		$.ajax({
			url: 'assets/php_functions/phpFunctions.php',
			type: "POST",
			data: {	populateEditForm: 'true',
					editTag : tagId},
			success: function(data) {
				var error = 'error';
				if(data.match(error)) {
					window.scrollTo(0,0);
					$(".errorMessage").hide().html("There was an error populating the current settings for your pet. Please try again later.").fadeIn('slow');
				} else {
					$(".errorMessage").empty();	
					$("#editForm").html(data);
					finishFormSetup();
				}
			}
		});
	}
	
	function finishFormSetup() {
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
	}
	
</script>	

</head>
<body>
    <label for='pet'>Select a pet:</label>
	<select class="form-control" id='petSelect' name='pet'>
		<?php populateAllPetsSelectBox(); ?>
	</select>
	<br>
	<div id='editForm'></div>
</body>
</html>
