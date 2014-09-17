<?php
session_start();
$loggedIn = empty($_SESSION['user']) ? false : $_SESSION['user'];
	if ($loggedIn == true) {
		header("Location: home.php");
		exit;
	}
?>


<!DOCTYPE html>
<html>
<head>
<?php include_once 'loginFunctions.php'; ?>	

</head>
<body>

<?php
//fill this in as errors occur
//it will be printed at the top of the page
$message = "";

//once the button is clicked...
if(isset($_POST['register'])) {
	//generate a random salt
	$salt = rand();

	//store the submitted info into variables
	$user = $_POST['email'];
	$pass = sha1($_POST['password'] . $salt);
	$repeatpass = sha1($_POST['repassword'] . $salt);
	$fname = $_POST['first-name'];
	$lname = $_POST['last-name'];
	$secQues = $_POST['secQues'];
	$secAns = $_POST['secAns'];
	
	
	//check to see if the passwords match before proceeding
	if($pass != $repeatpass) {
		$message = "Passwords must match!";
	}
	//that is the last input that must be verified.
	//from here, continue with adding everything to the DB 
	//and redirecting the user. 
	else {
		
		//connects to the db
		$dbconn = dbconnect();
	
		//check to see if the entered username already exists in the db
		//create a query to search for the entered username
		$storedname = "SELECT * FROM $schema.authentication WHERE user_email = $1";
		
		//prepare a statement for the query
		$searchprep = pg_prepare($dbconn, "search", $storedname);
		
		if($searchprep) {
			//execute the query
			$searchresult = pg_execute($dbconn, "search", array($user));
			
			//this will return the number of rows, if any, that were returned by the query. 0 means the username does not exist, 1 means it already exists. 
			$found = pg_num_rows($searchresult);
			
			//free result in case we want to use it again
			pg_free_result($searchresult);
			
		} else {
			$message = "Sanitizing username check failed";
		}
		
		//check if the row already exists before continuing 
		if($found > 0) 
		{
			$message = "That user name has already been registered by another user!"; 
		}		
		//this code will only execute if the entered user name does not already exist
		else {
		
			//store the user email, password hash, randomly generated salt, first name,last name,security question,
			//and security answer in the DB
			$query = "INSERT INTO $schema.authentication VALUES ($1, $2, $3, $4, $5, $6, $7)";
					
			//prepare the query
			$insertPrep = pg_prepare($dbconn, "register", $query);
			
			if($insertPrep) {
			//execute the query
			$result = pg_execute($dbconn, "register", array($user, $pass, $salt, $fname, $lname, $secQues, $secAns));
			//free result like before for later use
			pg_free_result($result);
			} else {
				echo $message = "sanitizing input failed for registration";
			}
			 
			$_SESSION['fname'] = $fname; 
			//set the session variable to 1 to tell other pages the user is logged in
			$_SESSION['login'] = 1;
			//save the username of the logged in individual for later use on home.php
			$_SESSION['user'] = $user;
			
			//redirect the user to the index.php page
			header("Location: home.php");
			 
		}//end of else after checking if the username already exists 	 
	
	} //end of else after check for matching passwords
	
}

?>




	<!-- navbar -->
	<nav class="navbar navbar-default navbar-fixed-top">
		<div class='container'>
			<div class="navbar-header">
				<p class="navbarText brand navbar-text">Registration</p>
			</div>
		</div>
	</nav>
	<br><br><br>

	<center><h4 class='errorMessage' id="errorMessage" ><?php echo $message?></h4></center>
	
  <div data-role="main" class="container">
    <form method="post" action="registration.php">
		<label for='email'>Email Address:</label>
		<input type='email' class="form-control" name='email' required="required" id='email'>
		<br>
		<label for='password'>Password:</label>
		<input type='password' class="form-control" name='password' pattern=".{8,16}" title="Must be between 8 and 16 characters" required="required" id='password'>
		<br>
		<label for='repassword'>Retype Password:</label>
		<input type='password' class="form-control" pattern=".{8,16}"  name='repassword' title="Must be between 8 and 16 characters" required="required" id='repassword'>
		<br>
		<label for='first-name'>First Name:</label>
		<input type='text' class="form-control" required="required" name='first-name' id='first-name'>   
		<br>
		<label for='last-name'>Last Name:</label>
		<input type='text' class="form-control" required="required" name='last-name' id='last-name'> 
		<br>
		<label for='secQues'>Security Question:</label>
		<br>
		<select name = 'secQues' class="form-control">
		  <option value="" selected>----------------------------</option>
		  <option value="favMovie">What is your favorite movie? </option>
		  <option value="momMaidenName">What is your mother's maiden name? </option>
		  <option value="elementarySchool">What is the name of the elementary school you attended? </option>
		  <option value="dreamJob">What is your dream job? </option>
		</select>
		<br><br>
		<label for='secAns'>Security Answer:</label>
		<input type='text' class="form-control" required="required" name='secAns' id='secAns'> 
		<br>
		<input type='checkbox' required="required" id='tos'>
		<label id='terms' for='tos'>I have read and agree to the <a href='#'>Terms of Service</a></label>
		<br><br>
		<center><button type='submit' name='register' class='btn btn-default'>Sign up</button>
		<br>
		
    </form>
    
  </div>
<br><br>

</body>
</html>
