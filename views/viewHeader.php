<?php

// echoes a header bar with links to sections and user status info

class viewHeader{
	function __construct() { 
		global $sectionconfig, $sections, $section, $title, $logo1;
		
		echo '<div class="full">';
		
		// Logo and title
		if ($logo1) echo '<img src="' . $logo1 . '" style="height:36px;">';
		echo '<span class="head">' . $title . '</span>';							// the name of this system, as defined in config.php
		
		if ( $_SESSION['login']['status'] == 'loggedin' ) {
			// Main page
			echo '&thinsp;|&thinsp;<a href="/system/pages/main.php"><span style="';			// link to main page
			echo $section == 'main' ? ' color:yellow;' : ' color:skyblue;';				// highlight current page
			echo '">main</span></a>' . PHP_EOL;
			
			// Regular sections	
			foreach ( $_SESSION['login']['perm_sections'] as $onesection) {				// links to all sections
				echo '|&thinsp;<a href="/system/pages/sectionview.php?section=' . $onesection . '"><span';
				if ( $onesection == $section ) echo ' style="color:yellow;"';			// highlight current page
				echo '>' . $onesection . '</span></a>' . PHP_EOL;
			}
			
			// Print
			echo '&thinsp;|&thinsp;<a href="/system/pages/printview.php"><span style="';	// link to print preview page
			echo $section == 'print' ? ' color:yellow;' : ' color:skyblue;';			// highlight current page
			echo '">print</span></a>' . PHP_EOL;
			
			// Custom pages
			foreach ( $_SESSION['custompages'] as $custompage ) {
				echo '|&thinsp;<a href="/config/custompages/'. $custompage['page'] . '"><span style="';
				echo $section == $custompage['section'] ? ' color:yellow;' : ' color:skyblue;';				// highlight current page
				echo '">' . $custompage['section'] . '</span></a>' . PHP_EOL;
			}
		}
		
		new viewStatus(); 
		
		echo '</div>'; 
	}
}
?>


