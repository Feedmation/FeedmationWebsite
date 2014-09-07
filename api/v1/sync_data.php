<?php
include_once('objects/tag.php');

$feederid = $_GET['feederid'];
$func = $_GET['function'];

//variable for the schema used in the database.
//store it here so we can easily change it if need be. 
//to access it from ANY scope of ANY file use:
//$GLOBALS['schema']
//if already inside of double quotes then just use:
//$GLOBALS[schema]
$schema = "feedmati_system";

//connects to the db
$dbConnString = "host=173.254.28.90 options='--client_encoding=UTF8' user=feedmati_user dbname=feedmati_system password=PZi0wuz9n+XX";
$dbConn = pg_connect($dbConnString ) or die("Problem with connection to PostgreSQL:".pg_last_error());	

//Start query to check if feeder auth is valid
$authQuery = "SELECT * FROM $GLOBALS[schema].feeds WHERE feeder_id = $1";
$stmt = pg_prepare($dbConn,"auth",$authQuery);
		
echo pg_result_error($stmt);

//if statement won't prepare then return error else execute statment
if(!$stmt)
{
	header('Content-Type: application/json');
	echo json_encode(array("error" => "database query error"));
	return;

} else {
	
	$result =  pg_execute($dbConn,"auth",array($feederid));
	
	//if one feeder id match was found then run requested function.
	if(pg_num_rows($result)==1)
	{
		
		switch($func)
		{
			//data loging function
			case 'log_data':
				$parameters = array();
				$body = file_get_contents("php://input");
				$body_params = json_decode($body);
        		
        		if($body_params) {
            		foreach($body_params as $param_name => $param_value) 
            		{
                		$parameters[$param_name] = $param_value;
                	}
				header('Content-Type: application/json');
                echo json_encode(array("logData" => "Submited"));
                
            	} else {
            	
            	header('Content-Type: application/json');
            	echo json_encode(array("logdata" => "failed"));
            	
            	}
        	break;
 			
 			// function for providing per feeder with tag settings
 			case 'pull_settings':
				$tag1 = new Tag(1, '84003515CA', 3.5, 10, 11, 16, 20);
				$tag2 = new Tag(2, '84003515CA', 2.5, 10, 12, 16, 21);
				$tag3 = new Tag(3, '84003515CA', 1.5, 10, 11, 16, 22);
	
				header('Content-Type: application/json');
				//echo json_encode(array("blank" => 0));
				echo json_encode(array("tag" => 1, "tid" => "14003515CA", "a" => 3.01, "s1" => 10, "s1e" => 11, "s2" => 14, "s2e" => 17));
			break;
      
      		// function for providing pet feeder with feed now request info
      		case 'feed_now':
				$feedNow = array('f' => 1,'fa' => 2.01);
	
				header('Content-Type: application/json');
				echo json_encode($feedNow);
			break;
	
			default:
				header("HTTP/1.0 405 Method Not Allowed");
			break;
		}
		return;
			
	} else {
		echo json_encode(array("error" => "invalid feeder id"));
		return;
	}
}

pg_close($dbConn);
?>