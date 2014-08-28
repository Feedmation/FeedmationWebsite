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
		$('.errorMessage').empty();
	});

</script>

</head>

<body>
	<label for='pet'>Select a pet:</label>
	<select class="form-control" name='pet'>
		<?php populatePetsSelectBox(); ?>
	</select>
	<br>
	<table class='table'>
	   <h2>custom stats coming up next!</h2>
	</table>         
	 
	<center><a href="home.php" data-inline='true' class='btn btn-default backButton'>Go back to Feedmation Home</a></center>
</body>
</html>
















