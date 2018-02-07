<?php
class viewLoginSuccess {
	function __construct() {
		echo '<h2>Welcome ' . $_SESSION['login']['name'] . '!</h2><p>We are now redirecting you to the member area.</p>';
    	echo '<meta http-equiv="refresh" content="0; /system/pages/main.php" />';
		echo '<p><a href="/system/pages/main.php">Click here</a> if redirect is not working within 5 seconds.</p>';
	}
}
?>