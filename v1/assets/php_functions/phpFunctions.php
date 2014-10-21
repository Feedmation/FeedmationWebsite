<?php
if(session_id() == '') {
    session_start();
}
	$sitePath = dirname(dirname(__FILE__));
	include_once("../../loginFunctions.php");

	//function for populating the list of feeders on home.php
	function populateFeeders() {
		
		$dbconn = dbconnect();
		
		$selectFeeders = "SELECT * FROM $GLOBALS[schema].feeders WHERE user_email = $1";
		
		$selectFeedersPrep = pg_prepare($dbconn, "feeders", $selectFeeders);
		
		if($selectFeedersPrep) {
			$feedersResult = pg_execute($dbconn, "feeders", array($_SESSION['user']));
		} else {
			echo "Could not sanitize user name. Try again later.";
		}
		
		if($feedersResult) {
			if(pg_num_rows($feedersResult)==0) {
				echo "You haven't registered any feeders.\n Add one using the button below.";
				return;
			}
			$feeders = '';
			$i = 0;
			while($row = pg_fetch_assoc($feedersResult)) {
				$lastSynced = strtotime($row['last_synced']);		
				$thirtyMinsAgo = strtotime('-30 minutes');
				if($lastSynced >= $thirtyMinsAgo) {
					$onlineStatus = "online";
				} else {
					$onlineStatus = "offline";
				}
				echo $thirtyMinsAgo . " " . $lastSynced;
				$feeders.= "<script>
							function goToStats$i() {
								var feeder = '$row[feeder_id]';
								$.ajax({
								  url: 'assets/php_functions/phpFunctions.php?feeder=$row[feeder_id]',
								  type: 'POST',
								  success: function(data1) {
									if(data1.match('true')) {
										$.ajax({
										  url: 'stats.php?feederId=$row[feeder_id]',
										  type: 'POST',
										  success: function(data) {
											$('#feeders').empty().html(data);
										  }
										});	
									
									} else {
										$('.errorMessage').hide().html('You have not assigned any pets to this feeder.<br>Add a pet using the menu in the top right corner!').fadeIn('slow');
									}
		
								  }
								});
							}
							
							function deleteFeeder$i() {
								var feeder = '$row[feeder_id]';
								var dialog = confirm('Are you sure you want to delete feeder: $row[feeder_name]?');
								if (dialog == true) {
									reassignPetsAndDelete(feeder);
								} else {
									return;
								}
							}
							
							function reassignPetsAndDelete(feederId) {
								$.ajax({
									url: 'assets/php_functions/phpFunctions.php?feeder=' + feederId,
									type: 'POST',
									success: function(data) {
										if(data.match('true')) {
											$.ajax({
											  url: 'reassignDeletePets.php?deleteFeeder=' + feederId,
											  type: 'GET',
											  success: function(data) {
												$('#feeders').html(data);
											  }
											});
										} else {
											$.ajax({
												url: 'deleteFeeder.php?feederToDelete=' + feederId,
												type: 'POST',
												success: function(data) {
													$('#feeders').html(data);
													$('#deleteFeederBtn').html('Delete Feeder');
													$('#buttonBar').show();
												}														
											});
										}
									}	
								});
							}
							
							</script>
							<a onclick='goToStats$i()'><button class='feederBtn btn $onlineStatus btn-block'>$row[feeder_name]<span class='pull-right marginRight glyphicon glyphicon-chevron-right'></span></button></a>
							<a onclick='deleteFeeder$i()' class='delete'><img src='assets/images/delete.png' height='34' width='34'></a>
							<br><br>
							";
				
				
				$i++;
			}
			pg_free_result($feedersResult);
			echo $feeders;
			
		} else {
			echo "Could not query for Pet Feeders. Try refreshing the page";
		}
	
	}
	
	function populateFeedersSelectBox() {
			
		$dbconn = dbconnect();
	
		$selectFeeders = "SELECT * FROM $GLOBALS[schema].feeders WHERE user_email = $1";
		
		$selectFeedersPrep = pg_prepare($dbconn, "feeders", $selectFeeders);
		
		if($selectFeedersPrep) {
			$feedersResult = pg_execute($dbconn, "feeders", array($_SESSION['user']));
		} else {
			echo "Could not sanitize user name. Try again later.";
		}
		
		if($feedersResult) {
			$feeders = '';
			$i = 0;
			while($row = pg_fetch_assoc($feedersResult)) {
				$feeders.= "
							<option value='$row[feeder_id]'>$row[feeder_name]</option>
						   ";			
				$i++;
			}
			pg_free_result($feedersResult);
			echo $feeders;
			
		} else {
			echo "Could not query for Pet Feeders. Try refreshing the page";
		}
	
	}
	
	function populatePetsSelectBox() {
		
		$dbconn = dbconnect();
	
		$selectPets = "SELECT * FROM $GLOBALS[schema].rfid WHERE user_email = $1 and feeder_id = $2";
		
		$selectPetsPrep = pg_prepare($dbconn, "pets", $selectPets);
		
		if($selectPetsPrep) {
			$petsResult = pg_execute($dbconn, "pets", array($_SESSION['user'], $_GET['feederId']));
		} else {
			echo "Could not sanitize user name. Try again later.";
		}
		
		if($petsResult) {
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
	
	}
	
	//this code will execute when any feeder is clicked, or a delete feeder 'X' is clicked
	//will check to see if pets have been assigned to the feeder
	//returns true if yes, false if no
	if(isset($_GET['feeder'])) {
		$dbconn = dbconnect();
	
		$selectPets = "SELECT * FROM $GLOBALS[schema].rfid WHERE user_email = $1 AND feeder_id = $2";
		
		$selectPetsPrep = pg_prepare($dbconn, "pets", $selectPets);
		
		if($selectPetsPrep) {
			$petsResult = pg_execute($dbconn, "pets", array($_SESSION['user'], $_GET['feeder']));
		} else {
			echo "Could not sanitize user name. Try again later.";
		}
		unset($_GET['feeder']);
		if($petsResult) {
			if(pg_num_rows($petsResult)==0) {
				echo "false";
			} else {
				echo "true";
			}	
		} else {
			echo "false";
		}
	}
	
	//populate the pets to reassign when trying to delete a feeder
	//will print out the pets and a select box into a table row
	//the select box will contain all feeders available to reassign the pet to
	//and the delete pet option.
	function populatePetsToReassign() {
		$dbconn = dbconnect();
		
		$feederId = $_GET['deleteFeeder'];
		
		//build the select box, which will be the same for each pet
		$selectRemainingFeeders = "SELECT * FROM $GLOBALS[schema].feeders WHERE user_email = $1 AND feeder_id != $2";
		$selectRemainingFeedersPrep = pg_prepare($dbconn, "feederRemaining", $selectRemainingFeeders);
		
		if($selectRemainingFeedersPrep) {
			$selectRemainingFeedersResult = pg_execute($dbconn, "feederRemaining", array($_SESSION['user'], $feederId));
		} else {
			echo "Could not sanitize user name. Try again later.";
		}
		
		if($selectRemainingFeedersResult) {
			$feederOptions = "<select class='form-control'><option value='delete'>Delete Pet</option>";
			while($feederRow = pg_fetch_assoc($selectRemainingFeedersResult)) {
				$feederOptions.= "<option value='$feederRow[feeder_id]'>Reassign pet to $feederRow[feeder_name]</option>";
			}
			$feederOptions.= "</select>";
		} else {
			echo "Problem finding feeders. Try again later.";
		}
		
		//select all pets assigned to this feeder
		$selectPets = "SELECT * FROM $GLOBALS[schema].rfid WHERE user_email = $1 AND feeder_id = $2";
		$selectPetsPrep = pg_prepare($dbconn, "pets", $selectPets);
		
		if($selectPetsPrep) {
			$petsResult = pg_execute($dbconn, "pets", array($_SESSION['user'], $feederId));
		} else {
			echo "Could not sanitize user name. Try again later.";
		}
		
		if($petsResult) {
			$pets = '';
			while($row = pg_fetch_assoc($petsResult)) {
				$pets.= "
						<tr><td>$row[pet_name]</td><td><input type='hidden' name='tagId' value='$row[tag_id]'>$feederOptions</td></tr>				
						";
			}
		} else {
			echo "Problem finding pets. Try again later.";
		}
	
		echo $pets;
	}
	
	//called by populatePetsToReassign to get the select box of 
	//available feeders to reassign the pet to. 
	function populateAlternateFeeders($feederId, $tagId) {
		$dbconn = dbconnect();
		//build the select box, which will be the same for each pet
		$selectRemainingFeeders = "SELECT * FROM $GLOBALS[schema].feeders WHERE user_email = $1 AND feeder_id != $2";
		$selectRemainingFeedersPrep = pg_prepare($dbconn, "feederRemaining", $selectRemainingFeeders);
		
		if($selectRemainingFeedersPrep) {
			$selectRemainingFeedersResult = pg_execute($dbconn, "feederRemaining", array($_SESSION['user'], $feederId));
		} else {
			echo "Could not sanitize user name. Try again later.";
		}
		
		if($selectRemainingFeedersResult) {
			$feederOptions = "<select class='form-control' name='$tagId'><option value='delete'>Delete Pet</option>";
			while($feederRow = pg_fetch_assoc($selectRemainingFeedersResult)) {
				$feederOptions.= "<option value='$feederRow[feeder_id]'>Reassign pet to $feederRow[feeder_name]</option>";
			}
			$feederOptions.= "</select>";
		} else {
			echo "Problem finding feeders. Try again later.";
		}
		
		echo $feederOptions; 
	}
	
	//this will populate the stats table for the newly selected pet
	if(isset($_POST['populateStatsTable']) && !empty($_POST['populateStatsTable'])) {
		$dbconn = dbconnect();
		
		$tagId = $_POST['statsTag'];
		
		//select the stats for the current tagId
		$selectStats = "SELECT * FROM $GLOBALS[schema].stats WHERE tag_id = $1 AND user_email = $2";
		$selectStatsPrep = pg_prepare($dbconn, "selectStats", $selectStats);
		
		if($selectStatsPrep) {
			$selectStatsResult = pg_execute($dbconn, "selectStats", array($tagId, $_SESSION['user']));		
		} else {
			echo "error";
		}
		
		if($selectStatsResult) {
			if(pg_num_rows($selectStatsResult)==0) {
				echo "<h4>Your pet doesn't have any stats yet!</h4><br>";
			} else {
				//print out a table with all the stats 
				$row = pg_fetch_assoc($selectStatsResult);
				echo "
					<table class='table table-striped table-bordered table-hover'>
						<tr><td>Cups of food dispensed</td><td>$row[amtfedcups]</td></tr>
						<tr><td>Cups of food eaten</td><td>$row[amtatecups]</td></tr>
						<tr><td>Pounds of food eaten</td><td>$row[amtateweight]</td></tr>
						<tr><td>Pet weight (lbs.)</td><td>$row[petweight]</td></tr>
					</table>
					
					";
			}
		} else {
			echo "error";
		}		
	}

?>














