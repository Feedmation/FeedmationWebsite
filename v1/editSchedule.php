<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.css">
<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="http://code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.js"></script>

<style type="text/css">
		
#submitButton {
	background:#0076F2;
	}
	
ul.ui-listview.ui-listview-inset.ui-corner-all.ui-shadow {
margin-top: 20px;
}

div.ui-btn.ui-input-btn.ui-corner-all.ui-shadow.ui-btn-inline {
margin-top: 20px;
}	
		
</style>

</head>
<body>

<div data-role="page">
  <div data-role="header">
  <h1>Edit Schedule</h1>
  </div>

  <div data-role="main" class="ui-content">
    <form method="post" action="#">
      <div class="ui-field-contain">
		  
		<label for='choosePet'>Select a pets schedule to edit:</label> 
		  <select name="pets" style='margin-bottom: 40px'>
			  <option value="max">Max</option>
		  </select> 
		  
		<ul data-role="listview" data-inset="true">
		  <li data-role="list-divider">Morning Schedule</li>
		  
		  <li>
			<label for='morning1'>Start Time:</label>
			<select name='morning1'>
				<option value='8am'>8:00 AM</option>
			</select>
		  </li>
		  
		  <li>			
			<label for='morning2'>End Time:</label>
			<select name='morning2'>
				<option value='12pm'>12:00 PM</option>
			</select>
		  </li>
		  
		  <li>			
			<label for='morningCups'>Cups of Food:</label>
			<select name='morningCups'>
				<option value='1cup'>1</option>
			</select>
		  </li>
		</ul>
		
		<ul data-role="listview" data-inset="true">
		  <li data-role="list-divider">Evening Schedule</li>
		  
		  <li>
			<label for='evening1'>Start Time:</label>
			<select name='evening1'>
				<option value='5pm'>5:00 PM</option>
			</select>			  
		  </li>
		  
		  <li>
			<label for='evening2'>End Time:</label>
			<select name='evening2'>
				<option value='9pm'>9:00 PM</option>
			</select>		  
		  </li>
		  
		  <li>			
			<label for='eveningCups'>Cups of Food:</label>
			<select name='eveningCups'>
				<option value='1cup'>1</option>
			</select>
		  </li>
		  
		</ul> 
         
        <center><input type="submit" id='submitButton' data-inline="true" value="Update Schedule"></center>
      </div>
     
    </form>
  </div>
  
</div>

</body>
</html>
