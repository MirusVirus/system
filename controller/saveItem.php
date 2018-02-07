<?php

// Takes input (most likely from $input.php) and saves a new item to db
// $data structure: 
// [data] => Array
//         (
//             [action] => <empty if insert> | 'update'
//             [section] => <section>
//             [id] => <empty if insert> | <id>
//             [column1] => value1
//             [column2] => value2
//             ...
//         )


class saveItem {
	function __construct($data) {
		global $sectionconfig, $general_columns;
		$this->data = $data;
		$this->action = ($data['action'] == 'update') ? 'update' : 'insert' ; 
		$this->section = $data['section'];
		if ($this->action == 'update') $this->id = $data['id'];
		$this->columns = $sectionconfig[$this->section]['columns'];
		
		// manipulate data here if needed, depending on section
		switch ( $this->section ) {
			case 'hours': 
				if ( empty($this->data['name'] )) $this->data['name'] = 'CW ' . $this->data['cal_week'] . ' ' . $this->data['descr'];
				break;
			default: break;
		}
		
		// Generate query
    	$col_val = array();
    	$para_type = "";
    	$this->para_arr = array(& $para_type);
		$result = "";
		
		if ($this->action == 'update') {
      		foreach ($this->columns as $column) {
				$$column = str_replace("'", "&#039;", $this->data[$column]); // create a variable for each column and get their value from $data. 
				// we're only converting the single quote to html entities, so that we can include links and html format tags.  
        		$col_val[] = "`$column` = ?";
        		$this->para_arr[] = & $$column; // create array with parameters
        		$para_type .= 's'; // parameter placeholder: string
      		}
      		$this->sql = "UPDATE `$this->section` SET " .implode(',', $col_val). " WHERE id = $this->id";
    	}
		
		elseif ($this->action == 'insert') { 
			$columns = implode(', ', $this->columns);
			$values = "";
			foreach($this->columns as $column) {
				$$column = str_replace("'", "&#039;", $this->data[$column]); // create a variable for each column and get their value from $this->data
				// we're only converting the single quote to html entities, so that we can include links and html format tags.  
				$col_val[] = "?";
				$this->para_arr[] = & $$column; // create array with parameters
				$para_type .= 's'; // parameter placeholder: string
			}
			$this->sql = "INSERT INTO `$this->section` ( $columns ) VALUES ( " .implode(',', $col_val). " )";
		}
	}
	
	// execute save
	function process() {
		global $db;
		$this->stmt = $db->prepare($this->sql);
		if($stmt === false) { trigger_error('Wrong SQL: ' . $this->sql . ' Error: ' . $db->errno . ' ' . $db->error, E_USER_ERROR);	} 
		else {
			call_user_func_array(array($this->stmt, 'bind_param'), $this->para_arr);
			$this->stmt->execute();
			$result = $this->stmt->get_result();
		}
		if ( $result !== false || $result !== "" ) { // Success
			$this->result = 'success';
			if ($this->action == 'insert') {
				$this->id = $db->insert_id; // gets the id of this db insert
				$db->query("UPDATE `$this->section` SET created = NOW() WHERE id = $this->id");
			}
			$this->message[] = (($this->action == 'update') ? 'Updated ' : 'Created new ') . type($this->section) . ': ' . $this->data['name'];
		}
	}
	
	// check if other items have same rank; if so, push their ranks down 
	function fixRank() {
		if ( !empty ( $this->data['rank'] )) { 
			$competing_item = checkRank($this->section, $this->id, $this->data['rank']); 
			$rank = $this->data['rank'];
			while ( !empty ( $competing_item )) {
				$newrank = $rank + 1; 		
				changeRank($this->section, $competing_item, $newrank);
				$_SESSION['message'][] = 'Changed rank of ' . type($this->section) . ' ' . $competing_item. ' to ' . $newrank;
				$id = $competing_item;
				$rank = $newrank; 
				$competing_item = checkRank($this->section, $id, $rank);
			}
		}
	}
}