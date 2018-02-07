<?php
class viewLogout {
	function __construct() {
		global $texts;
		
		echo '<h2>' . $texts['logout_success'][$_SESSION['lang']]. '</h2>';
		echo '<p>' . $texts['logout_redirect'][$_SESSION['lang']]. '</p>';
    	echo '<meta http-equiv="refresh" content="2; /system/index.php" />';
		echo '<p>' . $texts['redirect'][$_SESSION['lang']]. '</p>';
	}
}
?>