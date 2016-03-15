<?php
require_once 'header.php';
if( isset($_SESSION['gp_user_pg']) ){

if ( isset($_GET['msg']) ){
	if ( $_GET['msg'] == 'correct' ){
		echo "<p>Reservation properly made.</p>";
	} else if ( $_GET['msg'] == 'hallOverflow' ){
		echo "<p>Reservation not made, more than 100 people for the interval chosen.</p>";
	} else if ( $_GET['msg'] == 'invalidInput' ){
		echo "<p>Invalid input. Please check the inserted data.</p>";
	} else if ( $_GET['msg'] == 'sr' ){
		$_SESSION['showRes'] = 'yes';
	} else if ( $_GET['msg'] == 'deleteOK' ){
		echo "<p>Reservation correctly deleted.</p>";
	}
}

// display reservations

reservation_list('yes');

// display form for new reservations

	$thiU = $_SESSION['gp_user_pg'];
		
	echo <<<FORM1_
	
	<div id=newRes>
	<form method='post' action='checkReservation.php' class='myformstyle'>
	<h1>New Reservation Form</h1>
	<label><span>People Number </span><input type='text' name='peopleC' title='Number of people for the event'/><label>
	<label><span>Start Time </span><input type='text' name='hsC' title='Start hour and minute, must be separated by :' placeholder=' HH:MM'/></label>
	<label><span>End Time </span><input type='text' name='heC' title='End hour and minute, must be separated by :' placeholder=' HH:MM'/></label>
	<input type='hidden' name='usr' value=$thiU>
	<input name='submitRes' type='submit' value='Insert'/>
	</form></div>
	
FORM1_;

// display users' reservations and allow delete

	echo <<<SHOW_USER_
	<script type="text/javascript"><!--
	
	document.write("<input type='button' value='Show Your Reservations list' onClick='showFunction()'/>");
	  function showFunction(){
	  	document.cookie="showReservations=yes; expires=0";
		location.reload();
	}
	//--> </script>
	<noscript>
	  <p>Javascript is currently disabled</p>
	  <a href="personalPage.php?msg=sr">Show your reservations list</a>
	</noscript>
	<br><br><br><a href='deleteUser.php'>Delete your account</a>
SHOW_USER_;
	
	if ( isset($_COOKIE['showReservations']) || isset($_SESSION['showRes'])){
		if ( isset($_COOKIE['showReservations']) && $_COOKIE['showReservations'] == 'yes' ){
			echo <<<DELETE_CK
			<script type="text/javascript"><!--
			document.cookie="showReservations=no;-1"
			//--> </script>
DELETE_CK;
		}
		if ( ( isset($_COOKIE['showReservations']) && $_COOKIE['showReservations'] == 'yes' )
			 || ( isset($_SESSION['showRes']) && $_SESSION['showRes']=='yes' )  ){
				user_reservations_list($thiU );
			}
		}
		
	
}
else if ( !isset($_SESSION['gp_guest_pg']) ) {
	echo "<p>Your timeout expired.<br>Please <a href='loginPage.php'>login</a> again.</p>";
} else {
	echo "<p>You must <a href='loginPage.php'>login</a> or <a href='registrationPage.php'>register</a> in order to see the content of this page</p>";
}

echo <<<NALL_
		
				</div><!--Main-->

		</div>  <!-- TableRow -->
		</div>	<!--TableContainer -->
</body>
</html>
NALL_;
?>
