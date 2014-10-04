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

$(document).ajaxStart(function() { 
	
	});
	
$(document).ajaxStart(function() { 
		//$('#feedmationNavbar').show();
	});
	
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
			<ul class='nav navbar-nav pull-right'>	
				<li id='navbarDropdown' class='dropdown'>
					<a href="#" class="dropdown-toggle glyphicon glyphicon-cog" data-toggle="dropdown"></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href='#' id='feedNow' name='feedNow' onclick='feedNow(); return false;'><span class='glyphicon glyphicon-time'></span> Feed Now!</a></li>
						<li><a href='#' name='addFeeder' onclick="addFeeder(); return false;"><span class='glyphicon glyphicon-plus'></span> Add Feeder</a></li>
						<li><a href='#' name='deleteFeeder' onclick="deleteFeeder(); return false;" ><span class='glyphicon glyphicon-trash'></span> Delete Feeder</a></li>
						<li><a href='#' name='addPet' onclick="addPet(); return false;"><span class='glyphicon glyphicon-plus'></span> Add Pet</a></li>
						<li><a href='dPet.php' id="deletePetBtn" name='deletePet'><span class='glyphicon glyphicon-trash'></span> Delete Pet</a></li>
						<li class="divider"></li>
						<li><a href="changeP.php"><span class='glyphicon glyphicon-user'></span> Change Password</a></li>
						<li><a href="logout.php"><span class='glyphicon glyphicon-off'></span> Logout</a></li>				
					</ul>
				</li>				
			</ul>
		</div>	
	</nav>
	<!-- end navbar -->
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
	</center>

</div>
<div class='loadingModal'><!-- this can be displayed when AJAX request are sent so the user knows something is happening --></div>
</body>
</html>

