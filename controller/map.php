<?php
// Create a new mapping between two items. 
// Both map1 and map2 use this class. 

class map {
	function __construct($mapped_section, $mapped_id, $mapling_section, $mapling_id, $rank) {
		global $db; 
		
		$this->mapped_section = $mapped_section; 
		$this->mapped_id = $mapped_id; 
		$this->mapling_section = $mapling_section; 
		$this->mapling_id = $mapling_id; 
		$this->rank = $rank; 
		
		// create a new entry in the `maps` table
		$message = array();
		$stmt = $db->prepare("INSERT INTO maps (mapped_section, mapped_id, mapling_section, mapling_id, rank) VALUES (?, ?, ?, ?, ?)");
		if($stmt === false) {
			trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $db->errno . ' ' . $db->error, E_USER_ERROR);
		} 
		else {
			$stmt->bind_param("sssss", $this->mapped_section, $this->mapped_id, $this->mapling_section, $this->mapling_id, $this->rank );
			$stmt->execute();
			$result = $stmt->get_result();
			$this->id = $stmt->insert_id; // id of last insert
			$message[] = 'Linked ' . sql($this->mapling_section, 'name', $this->mapling_id) . ' to ' . sql($this->mapped_section, 'name', $this->mapped_id);
		}
				
		// Check for competing maps with same rank
		$competing_map = checkMapRank($this->mapped_section, $this->mapped_id, $this->mapling_section, $this->mapling_id, $this>rank);
		
		while ( !empty ($competing_map)) {
			// $message[] = 'Competing link with same rank: ' . $competing_map;
			$newcompetingrank = $rank + 1; 
			$competing_mapling = changeMapRank($competing_map, $newcompetingrank);
			// $message[] = 'Changed rank of competing link to ' . $newcompetingrank;
			$rank = $newcompetingrank; 
			$competing_map = checkMapRank($mapped_section, $mapped_id, $mapling_section, $competing_mapling, $rank);
		}
		
		$this->message = implode($message, '<br>');
	}
	
	// create shortie objects that can be displayed after mapping
	
	function getMappedShortie() {												// for map1
		global $sectionconfig;
		$short = sql($this->mapped_section, $sectionconfig[$this->mapped_section]['short'][0], $this->mapped_id);
		$this->items[0] = new shortie($this->mapped_section, $this->mapped_id, $short, $this->id);
	}
	
	function getMaplingShortie() {												// for map2
		global $sectionconfig;
		$short = sql($this->mapling_section, $sectionconfig[$this->mapling_section]['short'][0], $this->mapling_id);
		$this->items[0] = new shortie($this->mapling_section, $this->mapling_id, $short, $this->id);
	}
	
}
?>