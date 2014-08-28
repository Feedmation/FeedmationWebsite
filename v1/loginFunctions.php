<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!--<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.css">
<script src="http://code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.js"></script>-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.0/jquery-ui.min.js"></script>
<link rel="stylesheet" media="all" type="text/css" href="assets/jquery-timepicker-master/jquery.timepicker.css" />
<script type="text/javascript" src="assets/jquery-timepicker-master/jquery.timepicker.js"></script>


<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.css">

<!-- Optional theme -->
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>


<link rel="stylesheet" href="assets/css/style.css">
<?php
include_once 'assets/php_functions/phpFunctions.php';
//variable for the schema used in the database.
//store it here so we can easily change it if need be. 
//to access it from ANY scope of ANY file use:
//$GLOBALS['schema']
//if already inside of double quotes then just use:
//$GLOBALS[schema]
$schema = "feedmati_system";

	function dbconnect()
	{
		//connects to the db
		$dbConnString = "host=173.254.28.90 options='--client_encoding=UTF8' user=feedmati_user dbname=feedmati_system password=PZi0wuz9n+XX";
		$dbConn = pg_connect($dbConnString ) or die("Problem with connection to PostgreSQL:".pg_last_error());
		return $dbConn;
	}

	function login()
	{
		$schema = "feedmati_system";
		$conn = dbconnect();	
		$user = $_POST['username'];
		$loginQuery = "SELECT * FROM $GLOBALS[schema].authentication WHERE user_email = $1";
		$stmt = pg_prepare($conn,"login",$loginQuery);
		echo pg_result_error($stmt);
		if(!$stmt)
		{
			echo "Error, can't prepare login query<br>";
			return;			
		}
		$result =  pg_execute($conn,"login",array($user));
		if(pg_num_rows($result)==0)
		{
			echo "Username or Password is incorrect";
			return;
		}	
		
		$saltQuery = "SELECT salt FROM $GLOBALS[schema].authentication WHERE user_email = $1";
		$saltPrepare = pg_prepare($conn, "salt", $saltQuery);
		
		if(!$saltPrepare) {
				echo "Could not sanitize query for salt";
				return;
		}
		$saltResult = pg_execute($conn, "salt", array($user));
		if(pg_num_rows($saltResult) > 0) {
			$saltRow = pg_fetch_assoc($saltResult);
			$salt = $saltRow['salt'];
			$salt = trim($salt);
			$saltedpw = sha1($_POST['password'] . $salt);
		}
	
		$userPassPrepare = pg_prepare($conn,"pwquery","SELECT * FROM $GLOBALS[schema].authentication WHERE user_email = $1 AND password_hash = $2");
		if(!$userPassPrepare)
		{
			echo "Error, can't prepare username password query<br>";
		}
		$user = $_POST['username'];
		$pwq = array($user,$saltedpw);
		$userPassResult = pg_execute($conn,"pwquery",$pwq);
		$numrows = pg_num_rows($userPassResult);
		//if there is a match it logs the person in and adds an entry to the log
		if($numrows==1) {
			
			$action = "login";
		
			$_SESSION['user']=$user;

			$userPassRow = pg_fetch_assoc($userPassResult);
			$fname = $userPassRow['fname'];
			$_SESSION['fname'] = $fname;
			
			header("Location: home.php");
		}
		else echo "Username or Password is incorrect";

		pg_close($conn);
	}


?>
