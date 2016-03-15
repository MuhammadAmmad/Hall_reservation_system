<?php

//HALL capacity
$hall_capacity = 100;

function mySessionCheck(){
	session_start();
	$idle=time();
	
	$new_s = false;
	$allowed_idle = 120; // 2 minutes

if  ( isset($_SESSION['gp_user_pg']) ) {
	if ( isset($_SESSION['gp_timer_pg']) ) {
		$t = $_SESSION['gp_timer_pg'];
		$idle = time() - $t;
	} else {
		$new_s = true;
	}
	if ( $idle < $allowed_idle ) {
		$_SESSION['gp_timer_pg'] = time(); // update use count timer
		return 1;
	} else if ( $new_s == true || $idle >= $allowed_idle ){
		session_unset(); 	// empty session
   		session_destroy();  // destroy session
   		return 0;
	}
	} else {
	// guest user
		$_SESSION['gp_guest_pg'] = 'guest';
		return 2;
	}
}

function myCookieCheck(){
	setcookie("tryCookie", "try");
	
	if ( !isset( $_COOKIE['tryCookie'] )) {
		return false;
	}
	
	return true;
}

function sanitizeString($var)
{
		$var = strip_tags($var);
		$var = htmlentities($var);
		$var = stripcslashes($var);
		return $var;
}

// database management functions

function my_db_connect(){
	$conn = mysqli_connect('localhost', 'pacellig', 'heispeno', 'pacellig');
	if( ! $conn ) {
		die('Connect error (' . mysqli_connect_errno() . ')' . mysqli_connect_error());
	} 	
	return $conn;
}

function login_check(){
if ( isset( $_POST['nameI'] ) && isset( $_POST['pswI'] ) ){
	if ( $_POST['nameI']!='' && $_POST['pswI'] != '' ){
		$connection = my_db_connect();
		// ok
		$user = mysqli_real_escape_string($connection, sanitizeString($_POST['nameI']));
		$psw = mysqli_real_escape_string($connection, sanitizeString($_POST['pswI']));
		
		$psw = md5($psw);
		
		if( ! $connection ) {
			mysqli_close($connection);
			die('Connect error (' . mysqli_connect_errno() . ')' . mysqli_connect_error());
		} 
		$myquery = "SELECT * FROM users WHERE username='$user' AND password='$psw'";
		$queryres = mysqli_query($connection, $myquery);
		$rowNumber = mysqli_num_rows($queryres);
		if ( $rowNumber != 0 ) {
			// ok, existing username and password
			session_start();
			$_SESSION['gp_timer_pg'] = time();
			$_SESSION['gp_user_pg'] = $user;
			mysqli_close($connection);
			header('Location: personalPage.php?msg=okLog');
		} else {
			// not ok
			mysqli_close($connection);
			header('Location: loginPage.php?msg=notOkLog');
		}
		
	} else {
		header('Location: loginPage.php?msg=emptyField');
	}
} else {
	header('Location: loginPage.php?msg=emptyField');
}
}

function insert_User(){
if ( ( isset($_POST['usernameC']) && isset($_POST['passwordC']) ) && ( $_POST['usernameC'] != '' && $_POST['passwordC'] != '' ) ){
	$connection = my_db_connect();
	
	// sanitize input
	$req_username = mysqli_real_escape_string($connection, sanitizeString($_POST['usernameC']));
	$req_password = mysqli_real_escape_string($connection,sanitizeString($_POST['passwordC']));
	$req_confirmpsw = mysqli_real_escape_string($connection,sanitizeString($_POST['confirmpasswordC']));
	
	if ( $req_confirmpsw != $req_password ) {
		header('Location: registrationPage.php?msg=noMatch');
		exit;
	}
	
	$req_password = md5($req_password);
	
	$myquery = "SELECT * FROM users WHERE username='$req_username'";
	
	$queryres = mysqli_query($connection, $myquery);
	$rowNumber = mysqli_num_rows($queryres);
	if ( $rowNumber == 0 ) {
		// ok, insert into database
		$sql = "INSERT INTO users (username, password) VALUES('" . $req_username ."' , '" .  $req_password . "' )";
		if ( mysqli_query($connection,$sql)) {
			mysqli_close($connection);
			session_start();
			$_SESSION['gp_timer_pg'] = time();
			$_SESSION['gp_user_pg'] = $req_username;
			header('Location: personalPage.php');
		} else {
			mysqli_close($connection);
			die("Query error." . mysqli_error($connection));
		}
		
	} else {
		// already picked
		mysqli_close($connection);
	header('Location: registrationPage.php?msg=alreadyU');
		
	}
} else {
	// redirect
	header('Location: registrationPage.php?msg=wrongitems');
}
}

function reservation_list($show_usr){
	$connection = my_db_connect();

	$myquery = "SELECT * FROM reservations ORDER BY people_n DESC";
	
	$queryres = mysqli_query($connection, $myquery);
	if ( !$queryres ){
		mysqli_close($connection);
		die("Query error." . mysqli_error($connection));
	}
	$rowNumber = mysqli_num_rows($queryres);
			
	if ( $rowNumber == 0 ) {
		echo "<div id='res_list'><p>List of Reservations is empty</p>";
	} else {
		echo "<div id='res_list'><p>List of Reservations</p>";
	if ( $show_usr == 'yes' ){ 	
	echo "<table><tr><th>User</th><th>Number of People</th><th>Start Time</th><th>End Time</th></tr>";
	
	for ($i=0; $i<$rowNumber; $i++) {
		$thisRow = mysqli_fetch_array($queryres);
		echo "<tr>";
		echo "<td>" . $thisRow["username"] . "</td>";
		echo "<td>" . $thisRow["people_n"] . "</td>";
		echo "<td>" . $thisRow["hour_s"] . "</td>";
		echo "<td>" . $thisRow["hour_e"] . "</td>";
		echo "</tr>";
	}
	
	echo "</table>";
	echo "</div>";
	} else {
		echo "<table><tr><th>Number of People</th><th>Start Time</th><th>End Time</th></tr>";

	for ($i=0; $i<$rowNumber; $i++) {
		$thisRow = mysqli_fetch_array($queryres);
		echo "<tr>";
		echo "<td>" . $thisRow["people_n"] . "</td>";
		echo "<td>" . $thisRow["hour_s"] . "</td>";
		echo "<td>" . $thisRow["hour_e"] . "</td>";
		echo "</tr>";
	}
	
	echo "</table>";
	}
	}
	
	
	mysqli_free_result($queryres);
	mysqli_close($connection);
}


function user_reservations_list( $thiU ){
	$connection = my_db_connect();
		
	$thiU = sanitizeString($thiU);
	
	$thiU = mysqli_real_escape_string($connection, $thiU);
	
	$myquery = "SELECT * FROM reservations WHERE username='$thiU' ORDER BY people_n DESC" ;
	
	$queryres = mysqli_query($connection, $myquery);
	if ( !$queryres ){
		mysqli_close($connection);
		die("Query error." . mysqli_error($connection));
	}
	$rowNumber = mysqli_num_rows($queryres);
		
	echo "<div id='res_list'><p>List of your active Reservations</p>";

	if ( $rowNumber == 0 ){
		echo "<p>You haven't made any reservation yet.</p>";
	}
	else {
		echo "<table><tr><th>User</th><th>Number of People</th><th>Start Time</th><th>End Time</th><th></th></tr>";

		for ($i=0; $i<$rowNumber; $i++) {
			$thisRow = mysqli_fetch_array($queryres);
			$delHS=$thisRow["hour_s"];
			$delHE=$thisRow["hour_e"];
			$delUSR=$thisRow["username"];
			$delPN=$thisRow["people_n"];
			echo "<tr>";
			echo "<td>" . $thisRow["username"] . "</td>";
			echo "<td>" . $thisRow["people_n"] . "</td>";
			echo "<td>" . $thisRow["hour_s"] . "</td>";
			echo "<td>" . $thisRow["hour_e"] . "</td>";
			// delete button
			echo "<td><form method='post' action='deleteReservation.php'>";
			echo "<input name='deleteBTN' type='submit' value='Delete'/>";
			echo "<input type='hidden' name='hidUSR' value=$delUSR>";
			echo "<input type='hidden' name='hidHS' value=$delHS>";
			echo "<input type='hidden' name='hidHE' value=$delHE>";
			echo "<input type='hidden' name='hidPN' value=$delPN></form></td>";
			
			echo "</tr>";
		}

		echo "</table>";
		echo "</div>";//res list
	}

	mysqli_free_result($queryres);
	mysqli_close($connection);
}

function check_reservation( $inputPeople, $inputHS, $inputHE, $inputMS, $inputME ){ // for overlap and #people
$connection = my_db_connect();

	$req_username = mysqli_real_escape_string($connection,sanitizeString($_POST['usr']));
	
	$inputPeople = mysqli_real_escape_string($connection, sanitizeString($inputPeople));
	$inputHS = mysqli_real_escape_string($connection, sanitizeString($inputHS));
	$inputHE = mysqli_real_escape_string($connection, sanitizeString($inputHE));
	$inputMS = mysqli_real_escape_string($connection, sanitizeString($inputMS));
	$inputME = mysqli_real_escape_string($connection, sanitizeString($inputME));
		
	$myquery = "SELECT * FROM reservations WHERE 
				  	( hour_e >= TIME('$inputHS:$inputMS:00') )	and ( hour_s <= TIME('$inputHE:$inputME:00') )
				  ";
	
	$queryres = mysqli_query($connection, $myquery);
	if ( !$queryres ){
		mysqli_close($connection);
		die("Query error." . mysqli_error($connection));
	}
	$rowNumber = mysqli_num_rows($queryres);
	
	if ( $rowNumber == 0 ){
		// no overlap 
		$queryI = "INSERT INTO reservations (username, people_n, hour_s, hour_e)
 		VALUES('" . $req_username . "' , '" .  $inputPeople . "' , " .  "TIME('$inputHS:$inputMS:00')" . " , " .  "TIME('$inputHE:$inputME:00')"  . ")";
		if ( mysqli_query($connection,$queryI)) {
			mysqli_close($connection);
			header('Location: personalPage.php?msg=correct');
		} else {
			mysqli_close($connection);
			die("Query error." . mysqli_error($connection));
		}
	} else {
		//overlap, check people_n
		$people_reserved=$inputPeople;
		
		for ($i=0; $i<$rowNumber; $i++) {
			$thisRow = mysqli_fetch_array($queryres);
			$people_reserved += $thisRow["people_n"];
		}
		
		if ( $people_reserved > 100 ){
			// too many people
			header('Location: personalPage.php?msg=hallOverflow');
			exit;
		} else {
		$queryI = "INSERT INTO reservations (username, people_n, hour_s, hour_e)
 		VALUES('" . $req_username . "' , '" .  $inputPeople . "' , " .  "TIME('$inputHS:$inputMS:00')" . " , " .  "TIME('$inputHE:$inputME:00')"  . ")";
		if ( mysqli_query($connection,$queryI)) {
			mysqli_close($connection);
			header('Location: personalPage.php?msg=$people_reserved');
		} else {
			mysqli_close($connection);
			die("Query error." . mysqli_error($connection));
		}
		}
		
	}
}

function delete_reservation(){
	
	$connection = my_db_connect();
		
	$delUSR = mysqli_real_escape_string($connection, sanitizeString($_POST['hidUSR']));
	$delHS=mysqli_real_escape_string($connection, sanitizeString($_POST['hidHS']));
	$delHE=mysqli_real_escape_string($connection, sanitizeString($_POST['hidHE']));
	$delPN=mysqli_real_escape_string($connection, sanitizeString($_POST['hidPN']));
	
	$myquery = 
			 "DELETE FROM reservations WHERE username='$delUSR' and hour_s=TIME('$delHS')
				and hour_e=TIME('$delHE') and people_n='$delPN' LIMIT 1" ;
		
	if ( mysqli_query($connection,$myquery)) {
			mysqli_close($connection);
			header('Location: personalPage.php?msg=deleteOK');
		} else {
			mysqli_close($connection);
			die("Query error." . mysqli_error($connection));
		}
}

function delete_User(){
if ( ( isset($_POST['usernameC']) && isset($_POST['passwordC']) ) && ( $_POST['usernameC'] != '' && $_POST['passwordC'] != '' ) ){
	$connection = my_db_connect();
	
	// sanitize input
	$req_username = mysqli_real_escape_string($connection, sanitizeString($_POST['usernameC']));
	$req_password = mysqli_real_escape_string($connection,sanitizeString($_POST['passwordC']));
	$req_confirmpsw = mysqli_real_escape_string($connection,sanitizeString($_POST['confirmpasswordC']));
	
	if ( $req_confirmpsw != $req_password ) {
		header('Location: deleteUser.php?msg=noMatch');
		exit;
	}
	else {
	$req_password = md5($req_password);
	
	$myquery = "SELECT * FROM users WHERE username='$req_username' and password='$req_password'";
	
	$queryres = mysqli_query($connection, $myquery);
	$rowNumber = mysqli_num_rows($queryres);
	if ( $rowNumber == 1 ) {
		// ok, delete from database
		$sql = "DELETE FROM users WHERE username='$req_username'";
		if ( mysqli_query($connection,$sql)) {
			mysqli_close($connection);
			session_start();
			session_unset();
			session_destroy();
			header('Location: index.php?msg=accountDeleted');
		} else {
			mysqli_close($connection);
			die("Query error." . mysqli_error($connection));
		}
		
	} else {
		mysqli_close($connection);
		header('Location: deleteUser.php?msg=wrongUSR');
	}
	}
	} else {
		// redirect
		header('Location: deleteUser.php?msg=wrongitems');
	}
}

?>