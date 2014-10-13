<?php
if(session_id() == '') {
    session_start();
}
	$sitePath = dirname(dirname(__FILE__));
	include_once("loginFunctions.php");
	
$feederID = $_REQUEST['feederID'];

$selectPets = "SELECT * FROM $GLOBALS[schema].rfid WHERE user_email = $1 and feeder_id = $2";
		
$selectPetsPrep = pg_prepare($dbconn, "pets", $selectPets);
		
if($selectPetsPrep) {
	$petsResult = pg_execute($dbconn, "pets", array($_SESSION['user'], $feederID));
} else {
		echo "Could not sanitize user name. Try again later.";
}
		
if($petsResult)
	{
		$pets = '';
		$i = 0;
		while($row = pg_fetch_assoc($petsResult)) {
			$pets.= "
					<option value='$row[tag_id]'>$row[pet_name]</option>
			 ";			
			$i++;
		}
		pg_free_result($petsResult);
		echo $pets;	
	} else {
		echo "Could not query for Pets. Try refreshing the page";
	}
?>