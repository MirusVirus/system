<?php
class viewLoginUnconfirmed {
	function __construct() {
		echo '<h1>Please be patient.</h1>';
		echo '<p>Your status still needs to be confirmed by our admin.<br>If you can\'t wait, please contact: ' . EMAIL_ADDRESS . '</p>';
		echo '<p><a href="/system/index.php">Try another login</a>';
	}
}
?>