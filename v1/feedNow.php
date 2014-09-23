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
<?php include_once 'loginFunctions.php'; ?>	


<script>
	$( document ).ready(function() {
		$("#buttonBar").hide();
		$(".errorMessage").empty();
	});
	
	$('#feedNowForm').on('submit', function (e) {

		e.preventDefault();
		  
		$.ajax({
			url: 'assets/form_processing/updateFeedNow.php',
			type: "POST",
			dataType: 'text',
			data: $("#feedNowForm").serialize(),
			success: function(data) {
				var error = "pendingRequest";
				if(data.match(error)) {
					window.scrollTo(0,0);
					$(".errorMessage").hide().empty().html("You already have a Feed Now request pending.\nResubmit this request once that one executes.").fadeIn('slow');
				} else {
					$("#feeders").html(data);
					$(".errorMessage").empty().html("Your Feed Now request has been submitted!");
					$("#buttonBar").show();
					$("#feedNow").show();
				}
			}
		});	
	});
</script>

</head>
<body>

	<form method='POST' id='feedNowForm'>
		<label for='feederId'>Choose a feeder:</label>
		<select name="feederId" required="required" class="form-control" id="feederId">
			<?php populateFeedersSelectBox(); ?>
		</select>    
        <br>
		<label for='cups'>How many cups of food?:</label>
        <input type='number' name='cups' class="form-control" step='0.01' pattern='[0-9]*' required='required' placeholder='example: 1.5'> 
        <br>
        <center><a href="home.php" data-inline='true' class='btn btn-default backButton marginRight'>Cancel Request</a> <button type='submit' id='feedNowSubmitBtn' class="btn btn-default marginLeft">Feed Now!</button></center>
     
	</form>

</body>
</html>
