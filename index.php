<?php
require_once 'myFunctions.php';
require_once 'header.php';

	if( isset($_GET['msg']) ){
		if ( $_GET['msg']=='loggedOut' ){
			echo "<p>Correctly logged out.<br>Hope to see you soon.</p>";
			unset($_GET['msg']);
			exit;
		}
	}
		if( isset($_GET['msg']) ){
		if ( $_GET['msg']=='accountDeleted' ){
			echo "<p>Account correctly deleted.";
			unset($_GET['msg']);
			exit;
		}
	}

	

reservation_list('no'); // show all the reservations without showing the user who performed it

	echo <<<END_
		</div><!--Main-->
		</div>  <!-- TableRow -->
		</div>	<!--TableContainer -->
	</body>
	</html>
END_;
?>