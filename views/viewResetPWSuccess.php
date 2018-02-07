<?php
class viewResetPWSuccess {
	function __construct() {
		echo '<h1>Done</h1><p>You have successfully reset your password and we\'re redirecting you to the user area.</p>';
    	echo '<meta http-equiv="refresh" content="3; /system/pages/main.php" />';
		echo '<p><a href="/system/pages/main.php">Click here</a> if redirect is not working within 5 seconds.</p>';
	}
}
?>