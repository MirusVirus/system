<?php

// Displays an overview of all sections, 
// their numbers of items, 
// mapping information as defined in $sectionconfig, 
// additional info as defined in $sectionconfig

class overviewdata {
	function __construct(){
		global $db, $sectionconfig;
		
		$this->sections = $_SESSION['login']['perm_sections'];				// all sections allowed for this user
		
		foreach ( $this->sections as $key => &$section ) { 							// Loop through allowed sections
		
			$sql = "SELECT id FROM `$section`";
			$result = $db->query($sql); 
		
			$section = array('name' => $section); 
			$section['count'] = $result->num_rows;							// Number of items in section
			
			$section['mapling_sections'] = mapling_sections($section['name']); // Mapling info	
			$section['mappable_sections'] = $sectionconfig[$section['name']]['map']; // Mapping target info 

		}
	}
}
?>