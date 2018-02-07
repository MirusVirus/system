<?php 
class shortie { // data for minimal display of an item
	function __construct($section, $id, $short, $link) {
		global $sectionconfig;
		$this->section = $section; 
		$this->id = $id; 
		$this->short = $short;
		$this->link = $link;	
	}
}
?>