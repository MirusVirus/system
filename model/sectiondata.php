<?php 

// basic parameters for a section, to know what to display

class sectiondata { 
	function __construct($section) {
		global $sectionconfig, $general_actions;
		$this->section = $section;
		
		if ($this->section == 'main') $this->actions[] = 'search'; 						// add more actions here as the need arises
		
		else { 																				// "regular" sections
			$this->columns = $sectionconfig[$section]['columns'];
			$this->sorting = implode( $sectionconfig[$section]['sorting'], ', '); 			// as defined for each section in config.php 
			$this->special_actions = $sectionconfig[$section]['actions']; 					// as defined for each section in config.php
			$this->actions = array_merge($general_actions, $this->special_actions); 		// $general actions defined in config.php
			$this->tags = array();
			if ( in_array('tags', $sectionconfig[$section]['columns'])) $this->tags = getTags($this->section); 
			
			// sections that can be mapped to this or that this can be mapped to
			// must consider user permissions
			$this->mappable_sections = array_intersect($_SESSION['login']['perm_sections'], $sectionconfig[$this->section]['map']); 
			$this->mapling_sections = array_intersect($_SESSION['login']['perm_sections'], mapling_sections($this->section));
		}
		$this->getDialogs(); 
	}
	
	function getDialogs() {
		global $sectionconfig, $dialog_actions; 
		$this->dialogs = array_intersect($dialog_actions, $this->actions); 
		if (!empty($this->mappable_sections)) array_push($this->dialogs, 'map1'); 
		if (!empty($this->mapling_sections)) array_push($this->dialogs, 'map2'); 
		if ( in_array('photos', $this->mapling_sections)) array_push($this->dialogs, 'urlphoto'); 
	}
	
	function getItems() { 																	// only gets the ID
		global $db;
		$sql = "SELECT id FROM `$this->section` ORDER BY $this->sorting";
		$result = $db->query($sql);
		$this->count = mysqli_num_rows($result); 
		while ($row = $result->fetch_assoc()) {	
			$id = $row['id'];
			$this->items[$id] = array('mapped' => ''); 										// Creating empty array, so js knows this is an object
		}
	}
}
?>