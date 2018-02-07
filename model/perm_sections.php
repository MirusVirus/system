<?php 

// sections the user role has permission for

class perm_sections {
	function __construct() {
		global $sectionconfig, $sections; 
		$this->sections = $sections; 
		foreach ($this->sections as $key=>$section ) {
	   		if (!in_array($_SESSION['login']['role'], $sectionconfig[$section]['perm'])) unset ($this->sections[$key]);
		}
		sort($this->sections);
     }
}
?>