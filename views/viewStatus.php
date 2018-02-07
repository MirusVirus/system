<?php

// show all kinds of status information, likely in the top right corner of the header bar

class viewStatus{
	function __construct(){
		global $uploaddir, $type, $texts;
		echo '';
		
		// user status
		echo '<div class="status" >';
		
		if ( $_SESSION['login']['status'] == 'loggedin' ) {
			echo '<a href="/system/logout.php"><button>' . $texts['Logout'][$_SESSION['lang']]. '</button></a></div>'; 
			echo '<div class="status" >' . $texts['loggedinas'][$_SESSION['lang']] . $_SESSION['login']['name'] . '<br>'; 
			echo $texts['yourrole'][$_SESSION['lang']] . $_SESSION['login']['role'] . '<br>'; 
		}
		else echo $texts['notloggedin'][$_SESSION['lang']] . '<br>';
			
		// language switches
		foreach ( $type['lang']['dropdown'] as $lang ) {
			if ( $lang !== $_SESSION['lang'] ) { 
				echo '<span style="margin-right:15px; cursor:pointer; text-decoration:underline;" onclick = "
						var data = { lang: \''. $lang . '\' };
						action = new Action(this, \'lang\', data ); 
						ajax(action); 
					">';
			}
			else echo '<span style ="margin-right:15px; font-weight:bold;">';
			echo $lang;
			echo '</span>'; 
		}
			
		echo '</div>';
			
		if ( $_SESSION['login']['photo']) echo '<div class="status" ><img src="' . $uploaddir . $_SESSION['login']['photo'] . '"></div>';
				
		echo '<div class="status" ><img id="ajaxloader" src="/system/img/loader_snake.svg" style="width:28px; height:auto; opacity:0;"></div>'; 
		
		echo '';
	}
}
?>