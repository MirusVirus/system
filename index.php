<?php 
session_start();
$section = 'login';
if ( !$_SESSION['lang'] ) $_SESSION['lang'] = 'en';								// Set default language
$_SESSION['message'] = array();

require $_SERVER['DOCUMENT_ROOT'] . '/system/documenthead.php';  				// includes <body> tag as well as jquery, sorttable.js, datepicker.js
// include resp. autoload config, functions and classes
require_once ($_SERVER['DOCUMENT_ROOT'] . '/system/autoload.php');
?>

<div class="msg msg_main">

<?php 

if( $_SESSION['login']['status'] == 'loggedin' ) new viewLoginSuccess();		// Successful login - redirect to /system/pages/main.php

elseif(!empty($_POST['name']) && !empty($_POST['password'])) { 					// User has submitted login data
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
	$id = sqlbyname('users', 'id', $name);
    if ( $id ) { // This user exists
		$password_stored = sql('users', 'password', $id);
		if ( password_verify($password, $password_stored)) { 					// Password is correct
			$status = sql('users', 'status', $id);
			if ( $status == 'confirmed' ) { 									// This user is confirmed and can proceed
				new sessiondata($name, $id); 									// adds user stuff to $_SESSION
				$userLog = new userLog($id); 									// saves latest login and updates login count

				new viewLoginSuccess(); 										// *** Successful login - redirect to /system/pages/main.php
			}
			else { new viewLoginUnconfirmed(); } 								// User not confirmed by admin yet
		}
		else { 																	// Password is not correct
			new viewLoginWrongpw(); 
			echo $password . '<br>'; 
			echo $password_stored . '<br>'; 
			
			new viewUserform('Login');
		}
	}
    else { new viewLoginUserunknown(); } 										// No user with this name found
}
else
{
    new viewUserform('Login');
}
?>

</div>
<script src="/system/res/js/functions.js"></script>
</body>
</html> 