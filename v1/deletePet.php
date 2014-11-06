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
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.css">
<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="http://code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.js"></script>
<!-- navbar -->
	<nav id='navbar' role="navigation" class="navbar navbar-default navbar-fixed-top">
		<div class='container'>
			<div class="navbar-header">
				<p class="navbarText pull-left navbar-text"><?php echo "$_SESSION[fname]'s Feedmation Home"; ?></p>
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
					<li><a href='dPet.php' id="deletePetBtn" name='deletePet'><span class='glyphicon glyphicon-trash'></span> Delete Pet</a></li>
					<li class="divider"></li>
					<li><a href="changeP.php"><span class='glyphicon glyphicon-user'></span> Change Password</a></li>
					<li><a href="logout.php"><span class='glyphicon glyphicon-off'></span> Logout</a></li>				
				</ul>
			</div>
		</div>	
	</nav>
	<!-- end navbar -->
</head>
<body>

<div data-role="page">
  <div data-role="header">
  <h1>Delete Pets</h1>
  </div>

  <div data-role="main" class="ui-content">
    <form method="post" action="#">
      
		<fieldset data-role="controlgroup">
		  <legend>Select the pet(s) to delete:</legend>
			<label for='rover'>Rover</label>
			<input type="checkbox" id='rover' value='rover'>
			<label for='max'>Max</label>
			<input type='checkbox' id='max' value='max'>			
			<label for='spots'>Spots</label>
			<input type='checkbox' id='spots' value='spots'>
		</fieldset>	 
         
        <center><input type="submit" id='submitButton' data-inline="true" value="Delete Selected"></center>
     
    </form>
  </div>
  
</div>

</body>
</html>

<?php

include_once 'loginFunctions.php';

	$dbConn = dbconnect();
	$tagID = $_GET['tag_id'];
	
	//delete the feeder from the feeder table
	$petDelete = "DELETE FROM $GLOBALS[schema].feeders WHERE tag_id = $1";
	
	$petDeletePrep = pg_prepare($dbConn, "petDelete", $petDelete);
	
	if($petDeletePrep) { 
		$petDeleteResult = pg_execute($dbConn, "petDelete", array($petID));	
	} 
	else 
	{
		echo "<p>Couldn't delete your pet from the feeder. </p>";
	}

	//delete from stats table
	$statsTblDelete = "DELETE FROM $GLOBALS[schema].stats WHERE tag_id = $1";
	
	$statsDeletePrep = pg_prepare($dbConn, "deleteStatsTbl", $statsTblDelete);
	
	if($statsDeletePrep) { 
		$statsTblDeleteResult = pg_execute($dbConn, "deleteStatsTbl", array($feederId));	
	} else {
		echo "<p>Couldn't delete your pet's stats from the stats table</p>";
	}
	
	//once all the necessary data has been delete, repopulate the feeder list on home.php
	$data = populateFeeders();
	echo $data;
	
?>


