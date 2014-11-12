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
	
	function loadPetWeightChart(tagId, feederId) {
		$.ajax({
			url: 'assets/php_functions/phpFunctions.php',
			type: "POST",
			data: {	populatePetWeightChart: 'true',
					statsTag : tagId,
					feederId : feederId},
			success: function(data) {
				var error = 'error';
				if(data.match(error)) {
					window.scrollTo(0,0);
					$(".errorMessage").hide().html("There was an error populating a chart for your pet. Try again later.").fadeIn('slow');
				} else {
					$(".errorMessage").empty();
					var chartData = $.parseJSON(data);
					var ctx = document.getElementById('petChart').getContext('2d');
					var newChart = new Chart(ctx).Line(chartData);
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
		loadPetWeightChart(tagId, feederId);
		$('select').change(function() {
			tagId = selectBox.options[selectBox.selectedIndex].value;
			loadStatsTable(tagId, feederId);
			loadPetWeightChart(tagId, feederId);
		});
		
	});

</script>

</head>

<body>
	<div class='well'>
		<?php populateStatsPageHeader($_GET['feederId']); ?>
	</div>
	<br>
	<label for='pet'>Select a pet:</label>
	<select class="form-control" id='petSelect' name='pet'>
		<?php populatePetsSelectBox(); ?>
	</select>
	<br>
	<div id='statsTable'> </div>
	<!-- Canvas on which the pet chart will be drawn -->
	<canvas id="petChart"></canvas>	
	<center><a href="home.php" data-inline='true' class='btn btn-default backButton'>Go back to Feedmation Home</a></center>
</body>
</html>
















