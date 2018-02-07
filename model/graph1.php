<?php 

// delivers data for springy.js graph view.
// All mapping info for an item, both mapped and maplings

class graph1 { 																				
	function __construct($section, $id) {
		global $db, $sectionconfig; 
		$this->section = $section; 
		$this->id = $id; 
		$this->short = sql($this->section, $sectionconfig[$section]['short'][0], $this->id);
		
		$this->nodes[] = $this->short; 
		
		// Narrow down to sections that are allowed for mapping to the graph section
		$this->mapling_sections = array_intersect($_SESSION['login']['perm_sections'], mapling_sections($this->section)); 
		$this->mappable_sections = array_intersect($_SESSION['login']['perm_sections'], $sectionconfig[$this->section]['map']); 
		
		// Get all maplings and their edges
		foreach ( $this->mapling_sections as $mapling_section ) {
			$maplings = (new maplings($this->section, $this->id, $mapling_section))->items; 
			foreach ($maplings as $mapling) {
				$this->nodes[] = $mapling->short; 
				$this->edges[] = array($mapling->short, $this->short); 
			}
		}
		// Get all mapped and their edges
		foreach ( $this->mappable_sections as $mappable_section) {
			$mappeds = (new mapped($this->section, $this->id, $mappable_section))->items; 
			foreach ($mappeds as $mapped) {
				$this->nodes[] = $mapped->short; 
				$this->edges[] = array($this->short, $mapped->short); 
			}
		}
	}
}
?>