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
	
div#select-4-button {
	margin-bottom: 25px;
	}

div#select-5-button {
	margin-bottom: 25px;
	}		
		
</style>

</head>
<body>

<div data-role="page">
  <div data-role="header">
  <h1>Feed Now</h1>
  </div>

  <div data-role="main" class="ui-content">
    <form method="post" action="#">
      <div class="ui-field-contain">
		  
        <label for="feeder">Select a Feeder:</label>
        <select name='feeder'>
			<option value='feeder1'>Kitchen</option>
        </select>
         
        <label for="amount">How many cups?:</label>
        <select name='amount'>
			<option value='1cup'>1</option>
        </select> 
         
        <center><input type="submit" id='submitButton' data-inline="true" value="Feed Now!"></center>
      </div>
     
    </form>
  </div>
  
</div>

</body>
</html>
