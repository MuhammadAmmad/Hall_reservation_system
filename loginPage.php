<?php
require_once 'header.php';
if( ! isset($_SESSION['gp_user_pg']) ){

if ( !isset($_GET['msg']) ){
	echo "<p>You can login using your credentials.<p>";
} else {
	if ( $_GET['msg'] == 'notOkLog' ){
		echo "<p>Wrong Username or Password.<p>";
	} else if ( $_GET['msg'] == 'emptyField' ){
		echo "<p>Invalid Input.<p>";
	}
}

echo <<<ALL1_
				
				<div id="loginDIV">
				<p>
				<form action="checkLogin.php" method="POST" class="myformstyle">
				<label><span>Username </span><input type="text" name="nameI" title="Insert your Username here"></label>
				<label><span>Password </span><input type="password" name="pswI" title="Insert your password here"></label>
				<span>&nbsp;</span><label><input type="submit" value="LogIn"></label>
				</form>
				</p>
				<p>New User? Register a new account for free <a href="registrationPage.php">HERE</a>.</p>
				</div>
				</div><!--Main-->

		</div>  <!-- TableRow -->
		</div>	<!--TableContainer -->
		
</body>
</html>
ALL1_;
} else {
	$thisU = $_SESSION['gp_user_pg'];
	echo "<p>You are already logged in as $thisU.<br><br>Aren't you $thisU? You can <a href='logOut.php'> log out</a>.</p>";
}
?>