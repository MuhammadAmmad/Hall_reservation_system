<?php

require_once 'myFunctions.php';

$validsession = mySessionCheck();
$valid = true;
if ( $validsession == 1 ) {

if ( isset($_POST['peopleC']) && isset($_POST['hsC'])  && isset($_POST['heC'])  ){
	
	$inputPeople= sanitizeString( $_POST['peopleC'] );
	$inputHS = sanitizeString($_POST['hsC']);
	$inputHE = sanitizeString($_POST['heC']);
	$inputMS=-1;
	$inputME=-1;
	sscanf($inputHS, "%d:%d", $inputHS, $inputMS);
	sscanf($inputHE, "%d:%d", $inputHE, $inputME);
	
	
	if( $inputPeople == '' || $inputHS == '' || $inputHE == '' || $inputMS == '' || $inputME == '' ){
		$valid = false;
	}
	if (  $inputHS == 0 || $inputHE == 0 || $inputMS == 0 || $inputME == 0 ){
			$valid = true;
	}
	if ( $inputPeople <= 0 || $inputPeople > 100 || !is_numeric($inputPeople) ){
		$valid = false;
	}
	if ( $inputHS >= 24 || $inputHE >= 24 || $inputHS < 0 || $inputHE < 0 || !is_numeric($inputHS) || !is_numeric($inputHE) ){
		$valid = false;
	}
	if ( $inputMS >= 60 || $inputME >= 60 || $inputMS < 0 || $inputME < 0 || !is_numeric($inputMS) || !is_numeric($inputME) ){
		$valid = false;
	}
	if ( $inputHS == $inputHE && $inputMS == $inputME ){
		$valid = false;
	}
	if ( $inputHS > $inputHE ){
		$valid = false; 
	} else if ( $inputHS == $inputHE ){
		if( $inputMS > $inputME ){
			$valid = false;
		}
	}
	
	if ( $valid ) {
		// query the database 
		check_reservation($inputPeople, $inputHS, $inputHE, $inputMS, $inputME);
	
	} else {
		// not valid input
		header('Location: personalPage.php?msg=invalidInput');
		exit;
	}
	
}
}// not valid session
else {
	header('Location: personalPage.php');
}
?>