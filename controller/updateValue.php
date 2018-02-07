<?php

// Updates one single value in a table row

class updateValue {
	function __construct($data) {
		global $db;
		$this->data = $data;
		$this->section = $data['section'];
		$this->id = $data['id'];
		$this->column = $data['column'];
		$this->value = $data['newVal'];

		
		 // Query
		$this->sql = "UPDATE `$this->section` SET $this->column = ? WHERE id = ?";
		
		$this->stmt = $db->prepare($this->sql);
		if($stmt === false) { trigger_error('Wrong SQL: ' . $this->sql . ' Error: ' . $db->errno . ' ' . $db->error, E_USER_ERROR);	} 
		else {
			$this->stmt->bind_param('ss', $this->value, $this->id);
			$this->stmt->execute();
			$result = $this->stmt->get_result();
		}
		if ( $result !== false || $result !== "" ) { // Success
			$this->result = 'success';
			$this->message[] = 'Updated ' . $this->column . ' of ' . type($this->section) . ' ' . $this->id;
		}
	}
	
	// check if other items have same rank; if so, push their ranks down 
	function fixRank() {
		if ( $this->column == 'rank' ) { 
			$this->message = 'Checking ranks ...';
			$competing_item = checkRank($this->section, $this->id, $this->value); 
			$rank = $this->value;
			while ( !empty ( $competing_item )) {
				$newrank = $rank + 1; 		
				changeRank($this->section, $competing_item, $newrank);
				$this->message = 'Changed rank of ' . type($this->section) . ' ' . $competing_item. ' to ' . $newrank;
				$id = $competing_item;
				$rank = $newrank; 
				$competing_item = checkRank($this->section, $id, $rank);
			}
		}
	}
}