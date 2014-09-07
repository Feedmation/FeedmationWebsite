<?php
include_once('objects/tag.php');
include_once ('../../loginFunctions.php');

$dbConn = dbconnect();
$feederid = $_GET['feederid'];
$func = $_GET['function'];

switch($func)
{
	
	case 'log_data':
		$parameters = array();
		$body = file_get_contents("php://input");
		$body_params = json_decode($body);
        	if($body_params) {
            	foreach($body_params as $param_name => $param_value) {
                	$parameters[$param_name] = $param_value;
                }
				header('Content-Type: application/json');
                echo json_encode(array("logData" => "Submited"));
            } else {
            	header('Content-Type: application/json');
            	echo json_encode(array("logdata" => "failed"));
            }
              	  	
    	break;
 
 	case 'pull_settings':

		if ( $feederid == "12345" ) 
		{

			$tag1 = new Tag(1, '84003515CA', 3.5, 10, 11, 16, 20);
			$tag2 = new Tag(2, '84003515CA', 2.5, 10, 12, 16, 21);
			$tag3 = new Tag(3, '84003515CA', 1.5, 10, 11, 16, 22);
	
			header('Content-Type: application/json');
			//echo json_encode(array("blank" => 0));
			echo json_encode(array("tag" => 1, "tid" => "14003515CA", "a" => 3.01, "s1" => 10, "s1e" => 11, "s2" => 14, "s2e" => 17));
	
		} else {
			
			echo json_encode(array("token" => "invalid"));
		}
		
      break;
      
      case 'feed_now':

		if ( $feederid == "12345" ) 
		{

			$feedNow = array('f' => 1,'fa' => 2.01);
	
			header('Content-Type: application/json');
			echo json_encode($feedNow);
	
		} else {
			
			echo json_encode(array("token" => "invalid"));
		}
		
      break;
	
	default:
		header("HTTP/1.0 405 Method Not Allowed");
		break;
}

	exit;
?>