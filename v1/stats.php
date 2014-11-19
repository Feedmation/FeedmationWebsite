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
	
	function loadStatsChart(tagId, feederId) {
		$.ajax({
			url: 'assets/php_functions/phpFunctions.php',
			type: "POST",
			data: {	populateStatsChart: 'true',
					statsTag : tagId,
					feederId : feederId},
			success: function(data) {
				var error = 'error';
				if(data.match(error)) {
					window.scrollTo(0,0);
					$(".errorMessage").hide().html("There was an error populating a chart for your pet. Try again later.").fadeIn('slow');
				} else {
					$(".errorMessage").empty();
					data = data.substring(data.indexOf("{"),data.lastIndexOf("}")+1);  // take out the JSON formatted string
					var chartData = JSON.parse(data);  // make it an object for chart.js to use
					var ctx = document.getElementById('statsChart').getContext('2d');
					var statsChart = new Chart(ctx).Line(chartData,{responsive: true, scaleBeginAtZero: true, legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend pull-left\"><% for (var i=0; i<datasets.length; i++){%><li><span  class=\"badge\" style=\"height:5px !important;width:5px !important;background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"});
					$('#chartLegend').html(statsChart.generateLegend());  // generate a legend for the chart
					//statsChart.update();  // for some reason the chart has to update once to avoid screwing up
					
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
		loadStatsChart(tagId, feederId);
		$('select').change(function() {
			tagId = selectBox.options[selectBox.selectedIndex].value;
			loadStatsTable(tagId, feederId);
			loadStatsChart(tagId, feederId);
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
	
	<!-- Legend for Chart -->
	<div id="chartLegend"></div>
	<!-- Canvas on which the pet chart will be drawn -->
	<canvas id="statsChart"></canvas>
	<center><a href="home.php" data-inline='true' class='btn btn-default backButton'>Go back to Feedmation Home</a></center>
</body>
</html>
















