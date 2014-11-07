/**
 * Any javascript needed to make AJAX requests
 * should be placed in this file
 * 
 * Authors: Feedmation Team
 * 
 * */
 
/// Called when the add feeder menu item is clicked
/// replaces the 'feeders' div with the contents of addFeeder.php   
function addFeeder() {
	$.ajax({
      url: 'addFeeder.php',
      type: "GET",
      success: function(data) {
		$("#feeders").html(data);
      }
	});	
}

/// Called when the edit feeder menu item is clicked
/// replaces the 'feeders' div with the contents of editFeeder.php
function editFeeder() {
	$.ajax({
      url: 'editFeeder.php',
      type: "GET",
      success: function(data) {
		$("#feeders").html(data);	
      }
	});
}

/// Called when the edit pet menu item is clicked
/// replaces the 'feeders' div with the contents of editPet.php
function editPet() {
	$.ajax({
      url: 'editPet.php',
      type: "GET",
      success: function(data) {
		$("#feeders").html(data);	
      }
	});
}

/// Called when the delete pet menu item is clicked
/// replaces the 'feeders' div with the contents of dPet.php
function dPet() {
	$.ajax({
      url: 'dPet.php',
      type: "GET",
      success: function(data) {
		$("#feeders").html(data);	
      }
	});
}

/// Called when the add pet menu item is clicked
/// replaces the 'feeders' div with the contents of addPet.php
/// Will display an error message if no feeders have been added
function addPet() {
	$('.errorMessage').empty();
	
	if(!$('.feederBtn').is(':visible')) {
		$.ajax({
			url: 'assets/form_processing/fetchFeeders.php',
			type: "GET",
			success: function(data) {
				$("#feeders").html(data);
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
		});	
	} else {
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
}

/// Called when the delete feeder menu item is clicked
/// Shows a red 'X' next to each feeder
/// clicking the 'X' will use AJAX to bring the user
/// to a page where they can reassign/delete all pets
/// currently associated to that feeder
function deleteFeeder() {
	$('.errorMessage').empty();
	
	if(!$('.feederBtn').is(':visible')) {
		$.ajax({
			url: 'assets/form_processing/fetchFeeders.php',
			type: "GET",
			success: function(data) {
				$('#feedNow').hide();
				$("#feeders").html(data);
				$('.btn').addClass('focus');
				$('.delete').show();
			}
		});	
	} else {
		$('.btn').addClass('focus');
		$('.delete').show();	
	}
}

/// Called when the feed now menu item is clicked
/// replaces the 'feeders' div with the contents of feedNow.php
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

/// Called when the change password menu item is clicked
/// replaces the 'feeders' div with the contents of changeP.php
function changeP() {
	$.ajax({
      url: 'changeP.php',
      type: "GET",
      success: function(data) {
		$("#feeders").html(data);
      }
	});	
}

/// Function to close the navbar menu when an item is clicked
$(document).ready(function () {
    $("#navbar li a").click(function(event) {
    // check if window is small enough so dropdown is created
		$("#navbarCollapse").removeClass("in").addClass("collapse");
    });
});

