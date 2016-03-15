<?php
if ($_SERVER['SERVER_PORT'] != 443) { // https port
	header("Location: https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
}
require_once 'myFunctions.php';
	$cookieEnabled = myCookieCheck();
	if ( !$cookieEnabled ){
		echo <<<NOCOOKIE_
	<!DOCTYPE html>
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Hall Reservation</title>
	<link href="styles.css" rel="stylesheet" type="text/css" media="screen" />

	</head>	<body>

		<div id="header">
			<h1>Welcome to the Hall Reservation system</h1>
		</div><!--Header-->
		
		<div id="tableContainer">
		<div id="tableRow">
			
				
			<div id="sidebar">
			</div><!--Sidebar-->
		
	<div id="main">	

	<p>Cookies must be enabled in order to navigate the website</p>
	<p><a href='index.php'>Accept</a></p>
	
	</div><!--Main-->

		</div>  <!-- TableRow -->
		</div>	<!--TableContainer -->
</body>
</html>
NOCOOKIE_;
exit;
}
	$session_return = mySessionCheck();
	if ( $session_return == 1 ) { 
	$user_logged = $_SESSION['gp_user_pg'];
	echo <<<ALLOWED_
	<!DOCTYPE html>
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Hall Reservation</title>
	<link href="styles.css" rel="stylesheet" type="text/css" media="screen" />
	
	</head>
	
	<body>

		<div id="header">
			<h1>Welcome to the Hall Reservation system</h1>			
			<a href='logOut.php'>Log Out</a>
			<p>Currently logged in as <i>$user_logged</i> </p>
			<noscript>INFO: Javascript is currently disabled on your browser.</noscript>
		</div><!--Header-->
		
		<div id="tableContainer">
		<div id="tableRow">
			
				
			<div id="sidebar">
				<ul>
				<p>Navigation bar</p>
				<li><a href="index.php"/>HOME</a><li>
				<li><a href="loginPage.php"/>Log In</a><li>
				<li><a href="personalPage.php"/>Personal Page</a><li>
				</ul>
			</div><!--Sidebar-->
		
			<div id="main">
	 

ALLOWED_;
	} else if ( $session_return == 0 ) {
		echo <<<NOTALLOWED_
	
	 <!DOCTYPE html>
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Hall Reservation</title>
	<link href="styles.css" rel="stylesheet" type="text/css" media="screen" />
  	
	</head>
	
	<body>

		<div id="header">
		<h1>Welcome to the Hall Reservation system</h1>		
		<a href='registrationPage.php'>Register a FREE account</a>
		<noscript>INFO: Javascript is currently disabled on your browser.</noscript>
		</div><!--header-->
		
		<div id="tableContainer">
		<div id="tableRow">
			
				
			<div id="sidebar">
				<ul>
				<p>Navigation bar</p>
				<li><a href="index.php"/>HOME</a><li>
				<li><a href="loginPage.php"/>Log In</a><li>
				<li><a href="personalPage.php"/>Personal Page</a><li>
				<li><a href="registrationPage.php"/>Registration Page</a><li>
				</ul>
			</div><!--Sidebar-->
		
			<div id="main">

NOTALLOWED_;
	} else if ( $session_return == 2 ) {	
	echo <<<GUEST_
	
	 <!DOCTYPE html>
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Hall Reservation</title>
	<link href="styles.css" rel="stylesheet" type="text/css" media="screen" />
	<script src="jquery.js"></script>
  	
	</head>
	
	<body>

		<div id="header">
		<h1>Welcome to the Hall Reservation system</h1>	
		<a href='registrationPage.php'>Register a FREE account</a>	
		<noscript>INFO: Javascript is currently disabled on your browser.</noscript>
		</div><!--header-->
		
		<div id="tableContainer">
		<div id="tableRow">
			
				
			<div id="sidebar">
				<ul>
				<p>Navigation bar</p>
				<li><a href="index.php"/>HOME</a><li>
				<li><a href="loginPage.php"/>Log In</a><li>
				<li><a href="personalPage.php"/>Personal Page</a><li>
				<li><a href="registrationPage.php"/>Registration Page</a><li>
				</ul>
			</div><!--Sidebar-->
		
			<div id="main">

GUEST_;
	}
?>