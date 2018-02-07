<?php 

// This manages pages that are specific to the project and not part of the default configuration. 
// It scans the folder /config/custompages. 

// Custompages are PHP documents and they are identified by $section. 
// Custompages information will be stored in $_SESSION upon login. 
// Custompages will be added to index / nav bar automatically. 
// compare to customviews.php

class custompages {
	function __construct() {
		
		// List class files
		$this->pages = array_diff( scandir($_SERVER['DOCUMENT_ROOT'] . '/config/custompages'), array('..', '.'));
		
		$this->sections = array(); 
		
		foreach ($this->pages as $page ) {
			include $_SERVER['DOCUMENT_ROOT'] . '/config/custompages/' . $page; 
			if ($section) {
				$this->sections[] = array( 'section' => $section, 'page' => $page);
			}
			else $this->sections[] = array( 'section' => 'nosection', 'page' => 'nopage');
		}
     }
}
?>