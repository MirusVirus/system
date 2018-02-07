<?php

// adds an item to the print queue in $_SESSION

class printItem {
	function __construct($section, $id) {
		global $sectionconfig;
		
		$this->section = $section;
		$this->id = $id; 
		
		// Add print and section array to $_SESSION if not there
		$_SESSION['print'] = $_SESSION['print'] ? : array(); 
		$_SESSION['print'][$section] = $_SESSION['print'][$section] ? : array(); 
		
		// add item to $_SESSION
		$this->short = sql($this->section, $sectionconfig[$this->section]['short'][0], $this->id); 
		array_push($_SESSION['print'][$section], array('section' => $this->section, 'id' => $this->id, 'short' => $this->short ));
		
		$this->message = 'Added ' . type($this->section) . ' ' . $this->id . ' to print queue';
		
	}
}