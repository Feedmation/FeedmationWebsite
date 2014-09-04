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
	
	$('#reassignPetsForm').on('submit', function (e) {
		e.preventDefault();
		
		var table = document.getElementById('reassignDeleteTable');
		var tagIdsToDelete = [];
		var tagIdsToReassign = [];
		var feederToReassignPet = [];
		var rowLength = table.rows.length;
		for(var i=1; i<rowLength; i+=1){
			var row = table.rows[i];
			var cell = row.cells[1];
			var tagId = cell.getElementsByTagName('input')[0].value;
			var selectBox = cell.getElementsByTagName('select')[0];
			var decision = selectBox.options[selectBox.selectedIndex].value;
			if(decision.match('delete')) {
				tagIdsToDelete.push(tagId);
			} else {
				tagIdsToReassign.push(tagId);
				feederToReassignPet.push(decision);
			}
		}
		
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
		
	/*	if(typeof tagIdsToReassign != "undefined" && tagIdsToReassign != null && tagIdsToReassign.length > 0){
			$.ajax({
				url: 'assets/form_processing/reassignPets.php',
				type: "POST",
				data: {tags: tagIdsToReassign,
					   feeders: feederToReassignPet
					  },
				success: function(data) {
					var error = 'error';
					if(data.match(error)) {
						window.scrollTo(0,0);
						$(".errorMessage").hide().html("There was an error reassigning your pet. Try again later.").fadeIn('slow');
					} else {
						$(".errorMessage").empty();
						$("#feeders").html(data);
						$("#buttonBar").show();
					}
				}
			});
		}
			*/
		$(document).ajaxStop(function() {
		//	alert('ajax done');
		});

	/*	$.ajax({
			url: 'assets/form_processing/reassignPets_deleteFeeder.php',
			type: "POST",
			dataType: 'text',
			data: $("#reassignPetsForm").serialize(),
			success: function(data) {
				var error = 'error';
				if(data.match(error)) {
					window.scrollTo(0,0);
					$(".errorMessage").hide().html("That pet tag has already been registered.<br>Try typing it again.").fadeIn('slow');
				} else {
					$(".errorMessage").empty();
					$("#feeders").html(data);
					$("#buttonBar").show();
				}
			}
		});*/	
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
