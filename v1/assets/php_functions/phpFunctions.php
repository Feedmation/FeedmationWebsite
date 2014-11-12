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
	
	//this function will populate a select box with every pet the logged in user has registered to the given feederId. 
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
	
	//this function will populate a select box with every pet the logged in user has registered.
	//this is different from the function above because it doesn't limit the results to the feederId. 
	function populateAllPetsSelectBox() {
		
		$dbconn = dbconnect();
	
		$selectPets = "SELECT * FROM $GLOBALS[schema].rfid WHERE user_email = $1";
		
		$selectPetsPrep = pg_prepare($dbconn, "pets", $selectPets);
		
		if($selectPetsPrep) {
			$petsResult = pg_execute($dbconn, "pets", array($_SESSION['user']));
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
		$feederId = $_POST['feederId'];
		
		//select the stats for the current tagId
		$selectStats = "SELECT SUM(amtfedcups) AS amtfedcupssum, SUM(amtatecups) AS amtatecupsSum, SUM(amtateweight) AS amtateweightSum FROM $GLOBALS[schema].stats WHERE tag_id = $1 AND feeder_id = $2";
		$selectStatsPrep = pg_prepare($dbconn, "selectStats", $selectStats);
		
		if($selectStatsPrep) {
			$selectStatsResult = pg_execute($dbconn, "selectStats", array($tagId, $feederId));		
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
						<tr><td>Cups of food dispensed</td><td>$row[amtfedcupssum]</td></tr>
						<tr><td>Cups of food eaten</td><td>$row[amtatecupssum]</td></tr>
						<tr><td>Pounds of food eaten</td><td>$row[amtateweightsum]</td></tr>
					<!--	<tr><td>Pet weight (lbs.)</td><td></td></tr> -->
					</table>
					
					";
			}
		} else {
			echo "error";
		}		
	}
	
	//this will populate the edit form for editing a pet with the current settings for the selected pet
	if(isset($_POST['populateEditForm']) && !empty($_POST['populateEditForm'])) {
		$dbconn = dbconnect();
		
		$tagId = $_POST['editTag'];	
		
		//select the defaults for the current tagId
		$selectDefaults = "SELECT * FROM $GLOBALS[schema].rfid WHERE tag_id = $1 AND user_email = $2";
		$selectDefaultsPrep = pg_prepare($dbconn, "selectDefaults", $selectDefaults);
		
		if($selectDefaultsPrep) {
			$selectDefaultsResult = pg_execute($dbconn, "selectDefaults", array($tagId, $_SESSION['user']));
			$row = pg_fetch_assoc($selectDefaultsResult);	
			//get all the time values to pre-populate the timepickers
			$startTime1 = $row['slot_one_start'] . ":00am";
			$endTime1 = $row['slot_one_end'] . ":00am";
			$startTime2 = intval($row['slot_two_start']);
			$endTime2 = intval($row['slot_two_end']);
			
			if($startTime2 != 12) {
				$startTime2 -= 12;
			}
			
			if($endTime2 != 12) {
				$endTime2 -= 12;
			}
			
			$startTime2 = strval($startTime2) . ":00pm";
			$endTime2 = strval($endTime2) . ":00pm";
							
		} else {
			echo "error";
		}
		
		echo "
			<form method='POST' id='updatePetForm'>	
				<input type='hidden' name='number' value=$tagId> 	  
				<label for='name'>Name of your Pet:</label>
				<input type='text' required='required' class='form-control' name='name' value='$row[pet_name]'>    
				<br>
				<label for='feederId'>Which Feeder should your pet use?</label>
				<select name='feederId' required='required' class='form-control' id='feederId'>";
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
					while($feederRow = pg_fetch_assoc($feedersResult)) {
						if($feederRow['feeder_id'] == $row['feeder_id']) {
							$feeders.= "
									<option selected='selected' value='$feederRow[feeder_id]'>$feederRow[feeder_name]</option>
								   ";	
						} else {
							$feeders.= "
									<option value='$feederRow[feeder_id]'>$feederRow[feeder_name]</option>
								   ";		
						}		
						$i++;
					}
					pg_free_result($feedersResult);
					echo $feeders;	
				}
				echo "	
				</select>
				<br><br>
				<label for='firstTimeSlot'>Select time slot for first eating window:</label>
				<div class='well' name='firstTimeSlot'>
					<label for='startTime1'>Start Time:</label>
					<input type='text' required='required' class='form-control' name='startTime1' id='startTime1' value=$startTime1><br> 
					<label for='endTime1'>End Time:</label>
					<input type='text' required='required' class='form-control' name='endTime1' id='endTime1' value=$endTime1> 
				</div>
				<label for='firstTimeSlot'>Select time slot for second eating window:</label>
				<div class='well' name='secondTimeSlot'>
					<label for='startTime2'>Start Time:</label>
					<input type='text' required='required' class='form-control timepicker' name='startTime2' id='startTime2' value=$startTime2><br> 
					<label for='endTime2'>End Time:</label>
					<input type='text' required='required' class='form-control' name='endTime2' id='endTime2' value=$endTime2> 
				</div> 
				<label for='feedAmount'>How many cups of food per eating window?</label>
				<input type='number' step='0.01' min='0.5' max='8' required='required' class='form-control' name='feedAmount' value=$row[feed_amount]> 
				<br><br>         
				<center><a href='home.php' data-inline='true' class='btn btn-default backButton marginRight'>Cancel Submission</a> <button type='submit' id='updatePetSubmitBtn' onclick='updatePet();return false;' class='btn btn-default marginLeft'>Update Pet Info</button></center>
			</form>
		";
	}
	
	// called by stats.php
	// this will display some basic feeder info on the stats page
	function populateStatsPageHeader($feederId)
	{		
		$dbconn = dbconnect();
		
		$selectFeederStats = "SELECT * FROM $GLOBALS[schema].feeders WHERE user_email = $1 AND feeder_id = $2";
		
		$selectFeederStatsPrep = pg_prepare($dbconn, "feederStats", $selectFeederStats);
		
		if($selectFeederStatsPrep)
		{
			$feederStatsResult = pg_execute($dbconn, "feederStats", array($_SESSION['user'], $feederId));
		} else {
			echo "Could not select stats for your feeder. Please try again later";
		}
		
		if($feederStatsResult) {
			$row = pg_fetch_assoc($feederStatsResult);
			$lastSynced = strtotime($row['last_synced']);		
			$thirtyMinsAgo = strtotime('-30 minutes');
			if($lastSynced >= $thirtyMinsAgo) {
				echo "<strong>Feeder Status:</strong> Online <span class='glyphicon glyphicon-ok green'></span><br>";
			} else {
				echo "<strong>Feeder Status:</strong> Offline <span class='glyphicon glyphicon-exclamation-sign red'></span><br>";
			}
			
			if($row['empty'] == "t") {
				echo "<strong>Feeder is out of food <span class='glyphicon glyphicon-exclamation-sign red'></span></strong>";
			} else {
				echo "<strong>Feeder has food <span class='glyphicon glyphicon-ok green'></span></strong>";
			}			
		} else {
			echo "There are no stats for your feeder.";
		}
	}

	// this will populate a chart for pet weight
	if(isset($_POST['populatePetWeightChart']) && !empty($_POST['populatePetWeightChart'])) {
		$dbconn = dbconnect();
		
		$tagId = $_POST['statsTag'];
		$feederId = $_POST['feederId'];
		
		//select the stats for the current tagId
		$selectPetWeight = "SELECT petweight, event_time FROM $GLOBALS[schema].stats WHERE tag_id = $1 AND feeder_id = $2 ORDER BY event_time ASC";
		$selectPetWeightPrep = pg_prepare($dbconn, "selectPetWeight", $selectPetWeight);
		if($selectPetWeightPrep) {
			$selectPetWeightResult = pg_execute($dbconn, "selectPetWeight", array($tagId, $feederId));		
		} else {
			echo "error";
		}
		
		if($selectPetWeightResult) {
			if(pg_num_rows($selectPetWeightResult)==0) {
				echo "<h4>Your pet doesn't have any stats yet!</h4><br>";
			} else {
				//print out a chart with all the weight stats 
				while($row = pg_fetch_assoc($selectPetWeightResult)){
					$event_time[] = new DateTime($row['event_time']);
					$petweight[] = $row['petweight'];
				};
				$string = "{
					\"labels\": [";
					foreach ($event_time as $time)
						$string .= "\"".$time->format('m/d/Y')."\",";
					$string = rtrim($string, ",");  // get rid of the last ","
					$string .= "],
					\"datasets\": [
						{
							\"label\": \"Pet Weight\",
							\"fillColor\": \"rgba(202,225,255,0.2)\",
							\"strokeColor\": \"rgba(220,220,220,1)\",
							\"pointColor\": \"rgba(220,220,220,1)\",
							\"pointStrokeColor\": \"#fff\",
							\"pointHighlightFill\": \"#fff\",
							\"pointHighlightStroke\": \"rgba(220,220,220,1)\",
							\"data\": [";
							foreach ($petweight as $weight)
								$string .= "\"$weight\",";
							$string = rtrim($string, ",");  // get rid of the last ","
							$string .= "]
						}
					]
				}";
				echo $string;
			}
		} else {
			echo "error";
		}		
	}
?>














