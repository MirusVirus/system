<?php 
session_start();
$section = 'login';
$_SESSION['message']= array();

require $_SERVER['DOCUMENT_ROOT'] . '/system/documenthead.php';  				// includes <body> tag as well as jquery, sorttable.js, datepicker.js
require_once ($_SERVER['DOCUMENT_ROOT'] . '/system/autoload.php');				// include resp. autoload config, functions and classes
?>

<div class="msg msg_main">

<?php

if(!empty($_POST['name']) && !empty($_POST['password']) && !empty($_POST['rpassword']) && !empty($_POST['email'])) { 		// User has submittes registration data
	$name = mysqli_real_escape_string($db, $_POST['name']);
	$password = mysqli_real_escape_string($db, $_POST['password']);
	$rpassword = mysqli_real_escape_string($db, $_POST['rpassword']);
	$email = mysqli_real_escape_string($db, $_POST['email']);
      
	if( sqlbyname('users', 'name', $name)) { 																				// User name taken
        echo '<h1>Aw</h1><p>Sorry, that name is taken already. Please <a href="/system/register.php">try another name.</a></p>';
     }
     else { 																												// User name available
		 if ( $password == $rpassword ) { 																					// Registration data correct
			$password_hash = password_hash($password, PASSWORD_BCRYPT);
			if ($sql = $db->prepare("INSERT INTO users (name, password, email) VALUES(?, ?, ?)")) {
				if (!$sql->bind_param('sss', $name, $password_hash, $email)) {
					echo '<h1>Yikes</h1><p>Sorry, your registration failed. Please contact our admin: ' . EMAIL_ADDRESS .'</p>';
					// for debugging uncomment next line
					// echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
				}
				else {
					$sql->execute();
					$sql->close(); // close prepared statement to save resources
					echo '<h1>Success</h1><p>Your account was created. We still need to confirm your registration; please be patient! See you soon.</p>';
					
					// e-mail administrator
					require($_SERVER['DOCUMENT_ROOT'] . "/system/res/php/PHPMailer/PHPMailer.php");
					require($_SERVER['DOCUMENT_ROOT'] . "/system/res/php/PHPMailer/SMTP.php");
					$mail = new PHPMailer; 																						// Send notice to admin
					$mail->addAddress(EMAIL_ADDRESS);     
					$mail->Subject = $register_subject;
					$mail->Body    = $name . $register_mailbody;
			
					if(!$mail->send()) {
						echo '<p>Message to admin team could not be sent. Please contact them about your pending registration: ' . EMAIL_ADDRESS . '</p>';
						echo'Mailer Error: ' . $mail->ErrorInfo;
					}
				}
			}
			else echo '<h1>Yikes</h1><p>Sorry, your registration failed. Please contact our admin: ' . EMAIL_ADDRESS .'</p>';   
		}
        else echo '<p><span class="uh">Passwords did not match. Please try again.</span></p>';
		new viewUserform('Register');
     }
}
else new viewUserform('Register');
?>
</div>
</body>
</html> 