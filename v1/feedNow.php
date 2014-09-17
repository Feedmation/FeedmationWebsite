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
