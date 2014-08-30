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
	
function addFeeder() {
	$.ajax({
      url: 'addFeeder.php',
      type: "GET",
      success: function(data) {
		$("#feeders").html(data);
      }
	});	
}

function addPet() {
	if($('#feeders :button').length == 0) {
		$('.errorMessage').hide().html("Add a feeder first so you can assign your pet to it!").fadeIn('slow');
		return;
	}
	
	$.ajax({
      url: 'addPet.php',
      type: "GET",
      success: function(data) {
		$("#feeders").html(data);
      }
	});	
	
}

function deleteFeeder() {
	if($('.delete').is(':visible')) {
		$('.delete').hide();
		$('#deleteFeederBtn').html('Delete Feeder');
	} else {
		$('.delete').show();
		$('#deleteFeederBtn').html('Cancel Delete');
	}
}

</script>
</head>
<body>
	<!-- navbar -->
	<nav class="navbar-default navbar-fixed-top">
		<div class='container'>
			<div class="navbar-header pull-left">
				<p class="navbarText brand navbar-text"><?php echo "$_SESSION[fname]'s Feedmation Home"; ?></p>
			</div>
			<div class="navbar-header pull-right">
				<p class="navbar-text">
					<a href="logout.php" class="btn btn-default pull-right">Logout</a>
				</p>	
			</div>
		</div>
	</nav>
	<br><br><br><br>
	
<div class="container">
	
	<div data-role="main" id="main-content" class="ui-content">
		<center>
			<div class="errorMessage"></div><br>
			<div id="feeders">
			<?php populateFeeders(); ?>
			</div>
		</center>
	</div>
	<br><br>	
	<center id="buttonBar">
	<button id="addFeederBtn" class='btn btn-default marginRight' name='addFeeder' onclick="addFeeder();" data-inline="true">Add Feeder</button>
	<button id="deleteFeederBtn" class='btn btn-default marginLeft' name='deleteFeeder' onclick="deleteFeeder();" data-inline="true">Delete Feeder</button>
	<button id="addPetBtn" class='btn btn-default marginLeft' name='addPet' onclick="addPet();" data-inline="true">Add Pet</button>
	</center>

</div>

</body>
</html>

