<?php

// Creates an object containing a list of items of one section. 
// If a condition is given, only those items where 
// - condition column is 1 (if type yesno)
// - condition column is 'active' (if type dropdown)
// - condition column is >0 (if type text)

class listItems {
	function __construct($section, $condition = null, $order = null) {
		global $db, $type;
		$this->section = $section;
		$this->condition = $condition; 
		$this->order = $order;
		
		// get data
		$sql = "SELECT id FROM $section"; 
		
		if ($this->condition) {
			switch ($type[$condition]['type']) {
				case ('yesno'): $sql .= " WHERE $condition = true"; break;
				case ('dropdown'): $sql .= " WHERE $condition = 'active'"; break;
				case ('text'): $sql .= " WHERE $condition > 0"; break;
				default: $sql .= " WHERE $condition > 0"; 	
			}
		}
		
		if ($this->order) $sql .= " ORDER BY $order"; 
		$result = $db->query($sql); 
		while ($row=mysqli_fetch_assoc($result)) {
			$this->items[] = new item($this->section, $row['id']); 
		}
	}
}
?>