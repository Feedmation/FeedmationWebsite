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

<style type="text/css">
		
#submitButton {
	background:#0076F2;
	}
		
</style>

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


