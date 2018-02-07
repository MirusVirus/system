<?php

// Displays an index of all sections, 
// their numbers of items, 
// mapping information as defined in $sectionconfig, 
// additional info as defined in $sectionconfig

class viewOverview{
	function __construct($overviewdata){
		global $db, $sectionconfig;
		
		foreach ( $overviewdata->sections as $section ) { 	// Loop through sections and display count, link to section and comments
			echo '<fieldset class="overview">';
			echo '<legend><h2>' . $section['count'] . ' ' . $section['name'] . '</h2></legend>';
			echo '<a href="/system/pages/sectionview.php?section=' . $section['name'] . '"><button>Manage</button></a>';
			
			// Comments
			echo '<span class="comment">' . $sectionconfig[$section]['explain'] . '<br>';  
			
			// Prepare comment strings
			$maplinginfo = $section['mapling_sections'] ?  ucfirst(implode(', ', $section['mapling_sections'])).' can be linked to '.$section['name'] : 'Nothing can be linked to '.$section['name'];
			$mappableinfo = $section['mappable_sections'] ? ucfirst($section['name']).' can be linked to '.(implode(', ', $section['mappable_sections'])) : ucfirst($section['name']).' can\'t be linked to anything';
			
			// Display comments
			echo $maplinginfo . '<br>';  
			echo $mappableinfo . '</span>';  
			
			echo '</fieldset>';
		}
		
	}
}
?>