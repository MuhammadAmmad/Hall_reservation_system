<?php
require_once 'header.php';

if ( ! isset($_GET['msg'])) {
	echo "<p>Please insert your credentials below in order to delete your account.</p>";
} 
else {
	 if ( $_GET['msg'] == 'wrongitems' ){
		echo "<h1>Please insert your credentials below in order to delete your account.</p><p>They cannot be empty</h1>";	
	} else if ( $_GET['msg'] == 'noMatch' ){
		echo "<p>Passwords don't match.</p>";
	} else if ( $_GET['msg'] == 'wrongUSR' ){
		echo "<p>Wrong username or password.</p>";
	}
}

echo <<<FORM_
	
	<form method="post" action="checkDelete.php" class="myformstyle">
	<label><span>Username </span><input type="text" id="usernameC" name="usernameC" title="Insert Username Here"></label>
	<label><span>Password </span><input type="password" id="passwordC" name="passwordC" title="Insert Password Here"></label>
	<label><span>Confirm Password </span><input type="password" id="confirmpasswordC" name="confirmpasswordC" 
							title="Insert the same password inserted above" ></label>
	<span>&nbsp;</span><label><input type="submit" value="Confirm Delete" name="tryReg"></label>
	</form>
FORM_;

?>