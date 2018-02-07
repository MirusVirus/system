<?php 
session_start();
$section = 'index';
$_SESSION['message']= array();

require $_SERVER['DOCUMENT_ROOT'] . '/system/documenthead.php';  				// includes <body> tag as well as jquery, sorttable.js, datepicker.js
require_once ($_SERVER['DOCUMENT_ROOT'] . '/system/autoload.php');				// include resp. autoload config, functions and classes
?>

<div class="msg msg_main">

<?php 
session_start();
session_destroy();
setcookie();

new viewLogout();

?>

</div>
</body>
</html>