<?php

require_once 'header.php';

$new_username='';
$new_password='';

if ( ! isset($_GET['msg'])) {
	echo "<p>Please insert your desired credentials below.</p>";
} 
else {
	if (  $_GET['msg'] == 'alreadyU' ){
		echo "<p>Please choose another username</p>";
	} else if ( $_GET['msg'] == 'wrongitems' ){
		echo "<h1>Please insert your desired credentials below.</p><p>They cannot be empty</h1>";	
	} else if ( $_GET['msg'] == 'noMatch' ){
		echo "<p>Passwords don't match.</p>";
	}
}
echo <<<FORM_
	
	<form method="post" action="checkRegistration.php" class="myformstyle">
	<label><span>Username </span><input type="text" id="usernameC" name="usernameC" title="Insert Username Here"></label>
	<label><span>Password </span><input type="password" id="passwordC" name="passwordC" title="Insert Password Here"></label>
	<label><span>Confirm Password </span><input type="password" id="confirmpasswordC" name="confirmpasswordC" 
							title="Insert the same password inserted above" ></label>
	<span>&nbsp;</span><label><input type="submit" value="Register" name="tryReg"></label>
	</form>
FORM_;

echo <<<END_
	
		
				</div><!--Main-->

		</div>  <!-- TableRow -->
		</div>	<!--TableContainer -->
		
</body>
</html>
END_;
?>