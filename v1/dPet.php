<?php
	session_start();
	$loggedIn = empty($_SESSION['user']) ? false : $_SESSION['user'];
	if ($loggedIn == false) {
		header("Location: index.php");
		exit;
	}
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

<!DOCTYPE html>
<html>
<head>
<?php include_once 'loginFunctions.php'; ?>	
<?php include_once 'phpFunctions.php'; ?>	



<script>

	function loadPetsSelect(feederId) {
		alert("test ");
		$.ajax({
			url: 'dPet.php',
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
					$("#statsTable").html(data);
				}
			}
		});
	}

$(document).ready(function() {	
$('select').change(function() '
{
			var e = document.getElementById("feederId");
			var feederId = e.options[selectBox.selectedIndex].value;
});
		});

</script>

<br>
<? 
		$dbconn = dbconnect();
	?>
	<!--Feeder-->
	<label for='pet'>Select a Feeder:</label>
	<select class="form-control" id='feederSelect' name='feeder'>
		<?php populateFeedersSelectBox(); ?>
	</select>
	<br><br>
	
	<?
		$feederID = $_POST['feeder'];
		
		if($feederID!=5)
			echo $feederID;
	?>
	
	<!--Pets-->
	<label for='pet'>Select a Pet:</label>
	<select class="form-control" id='petSelect' name='pet'>
		<?php populatePetsSelectBox(); ?>
	</select>
	<br><br>
			<center><button type='submit' name='deletePet' class='btn btn-default'>Delete Pet From Feeder</button>
		<br><br>
			<center><a href="home.php" data-inline='true' class='btn btn-default backButton'>Go back to Feedmation Home</a></center>
			
			
