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
	
	function loadStatsTable(tagId, feederId) {
		$.ajax({
			url: 'assets/php_functions/phpFunctions.php',
			type: "POST",
			data: {	populateStatsTable: 'true',
					statsTag : tagId,
					feederId : feederId},
			success: function(data) {
				var error = 'error';
				if(data.match(error)) {
					window.scrollTo(0,0);
					$(".errorMessage").hide().html("There was an error populating stats for your pet. Try again later.").fadeIn('slow');
				} else {
					$(".errorMessage").empty();
					$("#statsTable").html(data);
				}
			}
		});
	}
	
	$(document).ready(function() {	
		$("#buttonBar").hide();
		$('.errorMessage').empty();
		var feederId = "<?php echo $_GET['feederId']; ?>";
		var selectBox = document.getElementById("petSelect");
		var tagId = selectBox.options[selectBox.selectedIndex].value;
		loadStatsTable(tagId, feederId);
		
		$('select').change(function() {
			tagId = selectBox.options[selectBox.selectedIndex].value;
			loadStatsTable(tagId, feederId);
		});
		
	});

</script>

</head>

<body>
	<label for='pet'>Select a pet:</label>
	<select class="form-control" id='petSelect' name='pet'>
		<?php populatePetsSelectBox(); ?>
	</select>
	<br>
	<div id='statsTable'> </div> 
	<center><a href="home.php" data-inline='true' class='btn btn-default backButton'>Go back to Feedmation Home</a></center>
</body>
</html>
















