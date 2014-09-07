<?php
include_once('objects/tag.php');

//$url_elements = explode('/', $_SERVER['PATH_INFO']);
$authToken = $_GET['val1'];
$func = $_GET['val2'];

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

		if ( $authToken == "12345" ) 
		{

			$tag1 = new Tag('Rex', '1', '8', '4');
			$tag2 = new Tag('Rover', '.5', '8', '5');
			$tag3 = new Tag('Jake', '1.5', '9', '6');
		
			$tags = array( '1' => $tag1->getArray(), '2' => $tag2->getArray(), '3' => $tag3->getArray());
	
			header('Content-Type: application/json');
			echo json_encode($tags);
	
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