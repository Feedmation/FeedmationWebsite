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
				$onlineStatus = ($row['online_status']) ? 'online' : 'offline';
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
									$('.errorMessage').hide().html('You have not assigned any pets to this feeder.<br>Add a pet using the button below').fadeIn('slow');
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
											alert('create a page to reassign pets');
										} else {
											$.ajax({
												url: 'delete_feeder.php?feederId=' + feederId,
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
							<a onclick='goToStats$i()'><button class='btn $onlineStatus btn-default'>$row[feeder_name]</button></a>
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

?>














