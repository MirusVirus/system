<?php 

// This collects print options. 
// Each print option needs to be stored as a class file in folder /config/prints.
// This class then scans the directory and extracts the different types of print options.
//
// Different types of print options (must be indicated as a property in the print option class): 
//
// *** type->selectItemlist - 
// a choice of items of a particular section can be selected for printing (e.g. parts labels)
//
// *** type->fixedItemlist - 
// a fixed choice of items will be put together for printing (e.g. account list)
// 


class printoptions {
	function __construct() {
		
		// List class files
		$this->print_classes = array_diff( scandir($_SERVER['DOCUMENT_ROOT'] . '/config/prints'), array('..', '.'));
		
		$this->print_sections = array(); 
		
		// add information on print options to list
		foreach ($this->print_classes as &$print_class ) {
			$classname = pathinfo( $print_class, PATHINFO_FILENAME);		// convert file names into class names	
			$print_class = array();
			$print_class['classname'] = $classname;	
			$printobject = new $classname(); 
			$print_class['printtype'] = $printobject->printtype;
			$print_class['name'] = $printobject->name;
			if ( $printobject->controls ) $print_class['controls'] = $printobject->controls;
			if ( $printobject->section ) $print_class['section'] = $printobject->section;			
			
			// prepare array with print options, ordered by section
			if ( $print_class['printtype'] == 'sectionbased' ) {						
				$this->print_sections[$printobject->section][] = $print_class;
			}	   		
		}
     }
}
?>