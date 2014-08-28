<?php

	//connects to the db
	$dbConnString = "host=localhost options='--client_encoding=UTF8' user=feedmati_user dbname=feedmati_system password=PZi0wuz9n+XX";
	$dbConn = pg_connect($dbConnString ) or die("Problem with connection to PostgreSQL:".pg_last_error());
	echo $dbConn . "<br>";
	
	$user = "feedmation@feedmation.com";
	$query = "SELECT fName FROM feedmati_system.authentication WHERE user_email = $1";
	$stmt = pg_prepare($dbConn,"emailQuery",$query);

	echo pg_result_error($stmt);
	if(!$stmt)
	{
		echo "Error, can't prepare 1<br>";
		return;
			
	}
	$result =  pg_execute($dbConn,"emailQuery",array($user));
	if(pg_num_rows($result)==0)
	{
		echo "Login is incorrect<br>";
		return;
	}	
	$userid = pg_fetch_result($result,0,'fName');
	echo $userid;
	 
	pg_close($dbConn);

?>
