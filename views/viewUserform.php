<?php
// display a form that suits all the needs around signup and login

class viewUserform {
	function __construct($action) {
		global $token, $type, $texts; 
		
		// Headline
		echo '<h1>' . $texts[$action][$_SESSION['lang']] . '</h1>';
		
		// Instructions part 1
		switch ($action) {
			case 'Login': 
				echo '<p>' . $texts['login_welcome'][$_SESSION['lang']] . '</p>'; break; 
			case 'Register': 
				echo '<p>' . $texts['register_welcome'][$_SESSION['lang']] . '</p>' . PHP_EOL; break;
			case 'Reset password': 
				echo '<p>' . $texts['resetPW_welcome'][$_SESSION['lang']] . '</p>'; break; 
			case 'Set password': 
				echo '<p>' . $texts['setPW_welcome'][$_SESSION['lang']] . '</p>'; break; 
		}
		
		// Input form
		echo '<form class="input" method="post" action="';
		switch ($action) {
			case 'Login': 
				echo '/system/index.php'; break; 
			case 'Register': 
				echo '/system/register.php'; break;
			case 'Reset password': 
				echo '/system/resetPW.php'; break; 
			case 'Set password': 
				echo '/system/resetPW.php'; break; 
		}
		echo '">';
    	
		if ($action == 'Login' || $action == 'Register' || $action == 'Reset password')	echo '<input type="text" 	name="name" placeholder="' . $texts['username'][$_SESSION['lang']]. '"/>'  . PHP_EOL; 
		if ($action == 'Login' || $action == 'Register' || $action == 'Set password')	echo '<input type="password" name="password" placeholder="' . $texts['password'][$_SESSION['lang']]. '"/>' . PHP_EOL;
		if ($action == 'Register' || $action == 'Set password') 						echo '<input type="password" name="rpassword" placeholder="' . $texts['rpassword'][$_SESSION['lang']]. '">' . PHP_EOL;
		if ($action == 'Register' || $action == 'Reset password') 						echo '<input type="text" name="email" placeholder="e-mail"/></td></tr>' . PHP_EOL; 
		if ($action == 'Set password') 													echo '<input type="hidden" name="token" value="' . $token . '" />';
        echo '<input type="submit" name="' . $action . '" id="' . $action . '" value="' . $texts[$action][$_SESSION['lang']] . '" />';
		
		// Instructions part 2
		if ($action == 'Login') echo '<p>' . $texts['login_forgotPW'][$_SESSION['lang']] . '<p>';

    	echo '</form>';
		if ($action !== 'Login') echo '<p>' . $texts['backtologin'][$_SESSION['lang']]. '</p>';
		
		// Change language
		/*
		foreach ($type['lang']['dropdown'] as $lang ) {
			echo '<span style="margin-right:15px; cursor:pointer; text-decoration:underline;" onclick = "
					var data = { lang: \''. $lang . '\' };
					action = new Action(this, \'lang\', data ); 
					ajax(action); 
					">';
			echo $lang . '</span>'; 
		}
		*/
	}
}