<?php 

// This manages pages and views that are specific to the project and not part of the default configuration. 
// It scans the folders /config/custompages and /config/customviews. 

// Custompages are PHP documents and they are identified by $section. 
// Customviews are classes; to know where they belong, they have the property $this->section and $this->position. 

// Custompages/views information will be stored in $_SESSION upon login. 

// Custompages will be added to index / nav bar automatically. 
// Customviews need a line on the page: 
// if ($_SESSION['customviews'][$section]['first']) new $_SESSION['customviews'][$section]['first']();

class customviews {
	function __construct() {
		
		// List class files
		$this->view_classes = array_diff( scandir($_SERVER['DOCUMENT_ROOT'] . '/config/customviews'), array('..', '.'));
		
		$this->sections = array(); 
		
		foreach ($this->view_classes as &$view_class ) {
			$view_class = pathinfo( $view_class, PATHINFO_FILENAME);			// convert file names into class names
			$viewobject = new $view_class(); 
			$this->sections[$viewobject->section][$viewobject->position] = $view_class;  		
		}
     }
}
?>