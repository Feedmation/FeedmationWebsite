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
	$('.errorMessage').empty();
	if($('.delete').is(':visible')) {
		$('.delete').hide();
		$('#deleteFeederBtn').html('Delete Feeder');
	} else {
		$('.btn').addClass('focus');
		$('.delete').show();
		$('#deleteFeederBtn').html('Cancel Delete');
	}
}

function feedNow() {
	$.ajax({
      url: 'feedNow.php',
      type: "GET",
      success: function(data) {
		$('#feedNow').hide();
		$("#feeders").html(data);
      }
	});	
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
					<a href="changeP.php" class='btn btn-default pull-right'>Change Password</a>  
					<a href="logout.php" class="btn btn-default pull-right">Logout</a>
				</p>	
			</div>
		</div>
	</nav>
	<br><br><br><br>
	
<div class="container">
	
	<div data-role="main" id="main-content" class="ui-content">
		<button id='feedNow' class='btn btn-danger' name='feedNow' onclick='feedNow();'>Feed Now!</button>
		<center>
			<div class="errorMessage"></div><br>
			<div id="feeders">
			<?php populateFeeders(); ?>
			</div>
		</center>
	</div>
	<br><br>	
	<center id="buttonBar">
	<button id="addFeederBtn" class='btn btn-default' name='addFeeder' onclick="addFeeder();" data-inline="true">Add Feeder</button>
	<button id="deleteFeederBtn" class='btn btn-default marginLeft' name='deleteFeeder' onclick="deleteFeeder();" data-inline="true">Delete Feeder</button>
	<button id="addPetBtn" class='btn btn-default marginLeft' name='addPet' onclick="addPet();" data-inline="true">Add Pet</button>
	<button id="deletePetBtn" class='btn btn-default marginLeft' name='deletePet'  data-inline="true">Delete Pet</button>

	</center>

</div>
<div class='loadingModal'><!-- this can be displayed when AJAX request are sent so the user knows something is happening --></div>
</body>
</html>

