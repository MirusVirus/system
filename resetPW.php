<?php 
session_start();
$section = 'login';
$_SESSION['message']= array();

require $_SERVER['DOCUMENT_ROOT'] . '/system/documenthead.php';  				// includes <body> tag as well as jquery, sorttable.js, datepicker.js
require_once ($_SERVER['DOCUMENT_ROOT'] . '/system/autoload.php');				// include resp. autoload config, functions and classes
?>

<div class="msg msg_main">

<?php

// User has submitted name and e-mail
if(!empty($_POST['name']) && !empty($_POST['email'])) { 
	$name = $_POST['name']; 
	$email = mysqli_real_escape_string($db, $_POST['email']);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	$sql = "SELECT id, name FROM `users` WHERE name = '$name' AND email = '$email'";
	$result = $db->query($sql); 
	if (mysqli_num_rows($result) > 0) { 											// user found in db
		$row = $result->fetch_assoc();
		$id = $row['id'];
		$name = $row['name'];
		
		// create token
		$token = bin2hex(random_bytes(16)); 
		$sql = "UPDATE users SET token = '$token' WHERE id = $id"; 
		$db->query($sql); 
		
		// e-mail link with token
		require($_SERVER['DOCUMENT_ROOT'] . "/system/res/php/PHPMailer/PHPMailer.php");
		require($_SERVER['DOCUMENT_ROOT'] . "/system/res/php/PHPMailer/SMTP.php");

		$mail = new PHPMailer; 																						// Send notice to admin
		$mail->addAddress($email);     
		$mail->Subject = $resetPW_subject;
		$mail->Body    = 'Hi ' . $name . ',<br>' .  $texts['resetPW_mailbody'][$_SESSION['lang']] . '<p><a href="https://' .$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].'?token='.$token.'">' . $texts['Reset'][$_SESSION['lang']] . '</a>';
		
		if(!$mail->send()) {
			echo '<div class="startmsg"><h1>:(</h1>';
			echo '<p>We\'re having trouble e-mailing your reset link. Please contact ' . EMAIL_ADDRESS . ' and forward this error message:</p>';
			echo '<p>Mailer Error: ' . $mail->ErrorInfo . '</p></div>';
		}
		else echo 																	// Success
			'<div class="startmsg"><h1>' . $texts['Done'][$_SESSION['lang']] . '</h1>
			<p>' . $row['name'] . $texts['resetPW_instructions'][$_SESSION['lang']] . '</p>
			<p>' . $texts['backtologin'][$_SESSION['lang']] . '</p></div>'; 
	}
	else { 																			// e-mail not found
		echo '<div class="startmsg"><h1>Aw</h1><p>Sorry, we didn\'t find you in our records. Here is what you should do: <br>
			Contact our administrator, or <a href="register.php">register</a> again.</p></div>'; 
     }
}

// user has clicked reset link
elseif ($_GET['token']) { 
	$token = $_GET['token']; 
	new viewUserform('Set password');
}

// user has entered new password
elseif (!empty($_POST['password']) && !empty($_POST['rpassword'])) {
	$token = $_POST['token']; 
	$password = mysqli_real_escape_string($db, $_POST['password']);
	$rpassword = mysqli_real_escape_string($db, $_POST['rpassword']);
	if ( $password == $rpassword ) { // Passwords match
		$password_hash = password_hash($password, PASSWORD_BCRYPT);
		$sql = "SELECT * FROM users WHERE token = '$token'"; 
		$result = $db->query($sql); 
		$row = $result->fetch_assoc(); 
		$name = $row['name']; 
		$id = $row['id'];
		$sql = "UPDATE users SET password = '$password_hash', token = NULL WHERE id = $id";
		if ($result = $db->query($sql)) {
			new sessiondata($name, $id); // adds user stuff to $_SESSION
			new viewResetPWSuccess; 
		}
		else echo '<h1>:(</h1><p>Something went wrong. Please contact our admin: ' . EMAIL_ADDRESS .'</p>';   
	}
	else {
		echo '<p><span class="uh">' . $texts['Done'][$_SESSION['lang']] . '</span></p>';
		new viewUserform('Set password');
	}
}

// user needs to request reset link
else new viewUserform('Reset password');

?>

</div>
</body>
</html> 