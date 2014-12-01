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

<!-- Link to our JS file which contains ALL the JS needed 
     to load pages via AJAX when clicked from the navbar -->
<script src='assets/js/feedmation.js'></script>

</head>
<body>
	<!-- navbar -->
	<nav id='navbar' role="navigation" class="navbar navbar-default navbar-fixed-top">
		<div class='container feedmationNavbar'>
			<div class="navbar-header">
				<a href='home.php'><p class="navbarText pull-left navbar-text"><?php echo "$_SESSION[fname]'s Feedmation Home"; ?></p></a>
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<div id="navbarCollapse" class="collapse navbar-collapse">
				<ul id='navList' class='nav navbar-nav pull-right'>					
					<li><a href='#' id='feedNow' name='feedNow' onclick='feedNow(); return false;'><span class='glyphicon glyphicon-time'></span> Feed Now!</a></li>
					<li><a href='#' name='addFeeder' onclick="addFeeder(); return false;"><span class='glyphicon glyphicon-plus'></span> Add Feeder</a></li>
					<li><a href='#' name='editFeeder' onclick="editFeeder(); return false;"><span class='glyphicon glyphicon-cog'></span> Edit Feeder</a></li>
					<li><a href='#' name='deleteFeeder' onclick="deleteFeeder(); return false;" ><span class='glyphicon glyphicon-trash'></span> Delete Feeder</a></li>
					<li><a href='#' name='addPet' onclick="addPet(); return false;"><span class='glyphicon glyphicon-plus'></span> Add Pet</a></li>
					<li><a href='#' name='addPet' onclick="editPet(); return false;"><span class='glyphicon glyphicon-cog'></span> Edit Pet</a></li>
					<li><a href='#' id="deletePetBtn" onclick="dPet(); return false;" name='deletePet'><span class='glyphicon glyphicon-trash'></span> Delete Pet</a></li>
					<li class="divider"></li>
					<li><a href="#" name='changePassword' onclick="changeP(); return false;"><span class='glyphicon glyphicon-user'></span> Change Password</a></li>
					<li><a href="logout.php"><span class='glyphicon glyphicon-off'></span> Logout</a></li>				
				</ul>
			</div>
		</div>	
	</nav>
	<!-- end navbar -->
	<br><br><br>
	
<div class="container">	
	<div data-role="main" id="main-content" class="ui-content">
		<center>
			<div class="errorMessage"></div>
			<br>
			<div id="feeders">
				<?php populateFeeders(); ?> 
			</div>
		</center>
	</div>
	<br><br>	
	<center id="buttonBar"></center>
</div>
<div class='loadingModal'><!-- this can be displayed when AJAX request are sent so the user knows something is happening --></div>
</body>
</html>

