<?php 
// Prepare all possible mapping targets from a certain section. 
// If the section contains tags, a list of unique tags will be delivered, too. 

class allMappable {
	function __construct($section) {
		global $db, $sectionconfig;
		$this->section = $section; 
		$short = $sectionconfig[$section]['short'][0];
		
		
		
		// Get data from db
		if ($this->tags) $sql = "SELECT id, $short, tags FROM `$this->section` ORDER BY name";
		else $sql = "SELECT id, $short FROM `$this->section` ORDER BY name";
		$result = $db->query($sql);
		
		$this->items = $result->fetch_all(MYSQLI_ASSOC);
	}
	
	// If section contains tags, get a list of unique tags
	function addTags() {
		if (in_array('tags', $sectionconfig[$section]['columns'])) $this->tags = getTags($this->section); 
	}
		
}
?>