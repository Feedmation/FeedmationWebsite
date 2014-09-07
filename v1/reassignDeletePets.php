<?php
	session_start();
	$loggedIn = empty($_SESSION['user']) ? false : $_SESSION['user'];
	if ($loggedIn == false) {
		header("Location: index.php");
		exit;
	}
	
include_once 'loginFunctions.php';
?>


<html>
<head>
	
<script>

	$( document ).ready(function() {
		$("#buttonBar").hide();
		$(".errorMessage").empty();
	});
	
	//run this block of javascript when the form is submitted
	$('#reassignPetsForm').on('submit', function (e) {
		e.preventDefault();
		//store the feederId that needs to be deleted once all the pet reassignment is done
		var deleteFeeder = '<?php echo $_GET['deleteFeeder']; ?>';
		
		//get the table via javascript
		var table = document.getElementById('reassignDeleteTable');
		//use these arrays to load the users choices
		var tagIdsToDelete = [];
		var tagIdsToReassign = [];
		var feederToReassignPet = [];
		var rowLength = table.rows.length;
		//for loop to go through each row of the table
		for(var i=1; i<rowLength; i+=1){
			var row = table.rows[i];
			var cell = row.cells[1];
			var tagId = cell.getElementsByTagName('input')[0].value;
			var selectBox = cell.getElementsByTagName('select')[0];
			//collect the decision the user chose from the select box
			//it will either be delete or a feeder name
			var decision = selectBox.options[selectBox.selectedIndex].value;
			//based on the decision, store that tag id and feeder id in the appropriate JS array
			if(decision.match('delete')) {
				tagIdsToDelete.push(tagId);
			} else {
				tagIdsToReassign.push(tagId);
				feederToReassignPet.push(decision);
			}
		}
		
		//if there are tagIds to delete, call the deletePet file
		if(typeof tagIdsToDelete != "undefined" && tagIdsToDelete != null && tagIdsToDelete.length > 0){
			$.ajax({
				url: 'assets/form_processing/deletePet.php',
				type: "POST",
				data: {tags: tagIdsToDelete},
				success: function(data) {
					var error = 'error';
					if(data.match(error)) {
						window.scrollTo(0,0);
						$(".errorMessage").hide().html("There was an error deleting your pet. Try again later.").fadeIn('slow');
					} else {
						$(".errorMessage").empty();
					}
				}
			});
		} 
		
		//if there are tagIds to reassign, call the reassignPets file
		if(typeof tagIdsToReassign != "undefined" && tagIdsToReassign != null && tagIdsToReassign.length > 0){
			$.ajax({
				url: 'assets/form_processing/reassignPets.php',
				type: "POST",
				data: {reassignTags: tagIdsToReassign,
					   feeders: feederToReassignPet
					  },
				success: function(data) {
					var error = 'error';
					if(data.match(error)) {
						window.scrollTo(0,0);
						$(".errorMessage").hide().html("There was an error reassigning your pet. Try again later.").fadeIn('slow');
					} else {
						$(".errorMessage").empty();
					}
				}
			});
		}
		
		//once all reassignments and deletes are done, this function will be called
		//it will delete the feeder, refresh the feeder list, and show that on home.php	
		$(document).ajaxStop(function() {
			$.ajax({
				url: 'deleteFeeder.php?feederToDelete=' + deleteFeeder,
				type: 'POST',
				success: function(data) {
					$('#feeders').html(data);
					$('#deleteFeederBtn').html('Delete Feeder');
					$('#buttonBar').show();
				}														
			});
		});

	});
	
</script>	

</head>
<body>
    
	<form method="POST" id="reassignPetsForm">		  
		<table id='reassignDeleteTable' class='table table-striped table-bordered table-hover'>
			<thead><tr><th>Pets</th><th>Select Option</th></tr></thead>
			<tbody>
				<?php populatePetsToReassign(); ?>
			</tbody>
		</table>
        <br><br>         
        <center><a href="home.php" data-inline='true' class='btn btn-default backButton marginRight'>Cancel Delete</a> <button type='submit' id='deleteFeederSubmitBtn' class="btn btn-default marginLeft">Delete Feeder</button></center>
    </form>

</body>
</html>
