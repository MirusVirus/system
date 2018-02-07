<?php 

// Creates a db table for a section, according to definitions in config.php

class table {
	function __construct($section) {
		global $db, $sectionconfig, $general_columns, $type; 			// From config.php
		
		// Prepare and check data
		$columns = array_unique(array_merge($general_columns, $sectionconfig[$section]['columns']));
		
		foreach ( $columns as $column ) {								// Check whether all columns are defined in $type
			if ( !$type[$column] ) echo '<p style="color:red;">Column "' . $column . '" is not defined in $type. </p>';
			else {
				if (empty($type[$column]['type'])) echo '<p class="uh">Type of "' . $column . '" is not defined. </p>';
				if (empty($type[$column]['sql'])) echo '<p class="uh">SQL type of "' . $column . '" is not defined. </p>';
				if (empty($type[$column]['label'])) echo '<p class="uh">Label of "' . $column . '" is not defined. </p>';
			}
		}
		
		foreach ( $sectionconfig[$section]['sorting'] as $sorting ) {	// Check whether sort keys match columns
			$array = explode(' ', $sorting, 2);
			$sortkey = $array[0];
			if (!in_array($sortkey, $columns)) echo '<p class="uh">"' . $sortkey . '" is not a valid sort key. </p>';
		}
		
		
		// Generate query
		$sql = "CREATE TABLE `$section` (";
		foreach ( $columns as $column ) { 								// columns with name and sql attributes from config.php
			$sql .= $column . ' ' . $type[$column]['sql'] . ', ';
		}
		$sql = rtrim($sql, ', '); 										// remove trailing comma
		$sql .= ' )'; 													// close bracket
		
		// Execute query
		if ( $db->query($sql) ) { 										// Success
			echo '<p class="comment">Created table ' . $section . '. MySQL query: ' . $sql . '"</p>';
		} else {
			echo '<p class="uh">Tried to create a table. MySQL query: ' . $sql . '. <br>Error: ' . $db->error . '"</p>';
		}
	}
}
	
?>