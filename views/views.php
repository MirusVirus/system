<?php 

class viewOverview{
	function __construct(){
		global $db, $sectionconfig;
		foreach ( $_SESSION['login']['perm_sections'] as $section ) { // Loop through sections and display count, link to section and comments
			$sql = "SELECT * FROM `$section`";
			$result = $db->query($sql); 
			${$section . 'count'} = $result->num_rows;
			echo '<fieldset class="overview">';
			echo '<legend><h2>' . ${$section . 'count'} . ' ' . $section . '</h2></legend>';
			echo '<a href="/system/pages/sectionview.php?section=' . $section . '"><button>Manage</button></a>';
			echo '<span class="comment">' . $sectionconfig[$section]['explain'] . '<br>';  
			// Prepare comment strings
			if ( count(mapling_sections($section)) > 0 ) {	$maplinginfo = ucfirst(implode(', ', mapling_sections($section))) . ' can be linked to ' . $section . '.'; }
			else { $maplinginfo = 'Nothing can be linked to ' . $section . '.'; }
			if ( count($sectionconfig[$section]['map']) > 0 ) {	$mappinginfo = ucfirst($section) . ' can be linked to ' . implode(', ', $sectionconfig[$section]['map']) . '.'; }
			else { $mappinginfo = ucfirst($section) . ' can`t be linked to anything else.'; }
			// Display comments
			echo $mappinginfo . '<br>';  
			echo $maplinginfo . '</span>';  
			echo '</pre></fieldset>';
		}
		// non section-related stuff
		echo '<fieldset class="overview">';
		echo htmlButton('search');
		echo '<legend><h2>Tools &amp; diagnostics</h2></legend>';
    	echo '<a href="/system/phpinfo.php" target="new"><button>PHP info</button></a>';
    	// echo '<a href="/system/views/diagnostics.php" target="new"><button>Full diagnostics</button></a>';
    	echo '<button onclick="ajax({action: \'checkAJAX\'});">AJAX check</button>';
		echo '</fieldset>';
	}
}


?>