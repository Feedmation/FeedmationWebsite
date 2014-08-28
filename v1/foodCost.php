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
	
div.ui-input-text.ui-body-inherit.ui-corner-all.ui-shadow-inset {
	margin-bottom: 25px;
	}
		
</style>

</head>
<body>

<div data-role="page">
  <div data-role="header">
  <h1>Food Cost</h1>
  </div>

  <div data-role="main" class="ui-content">
    <form method="post" action="#">
      <div class="ui-field-contain">
		  
        <label for="cost">Cost of the Bag:</label>
        <input type="text" name="cost">    
        
        <label for="weight">Weight of the Bag (lbs.):</label>
        <input type="text" name="weight"> 

         
        <center><input type="submit" id='submitButton' data-inline="true" value="Submit Info"></center>
      </div>
     
    </form>
  </div>
  
</div>

</body>
</html>
