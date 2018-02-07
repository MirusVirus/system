<?php 
// Checks, whether a table exists and has all columns required in $sectionconfig
// if not, creates a column or an entire table

class tableCheck {
	function __construct($section) {

		global $db, $sectionconfig, $type; 							// From config.php
		$sql = "SELECT 1 FROM `$section` LIMIT 1";
		$result = $db->query($sql); 
		
		// No table here? Let's create it
		if ( !$result ) new table($section); 						
		
		// Table is already there, so let's check for missing columns
		else { 	
			$columns = $sectionconfig[$section]['columns']; 
			array_push ( $columns, 'created'); 						// `created` must be in each table
			array_push ( $columns, 'updated'); 						// `updated` must be in each table
			
			foreach ( $columns as $column ) {
				
				// Check column definitions
				$datatype = $type[$column]['sql'];
				if (empty($type[$column]['type'])) echo '<p class="uh">Type of "' . $column . '" is not defined. </p>';
				if (empty($datatype)) echo '<p class="uh">SQL type of "' . $column . '" is not defined. </p>';
				if (empty($type[$column]['label'])) echo '<p class="uh">Label of "' . $column . '" is not defined. </p>';
				
				// Create column
				$sql = "SELECT $column FROM `$section` LIMIT 1";
				$result = $db->query($sql); 
				if ( !$result ) { 									// Columns missing? Let's create them.
					echo '<span class="comment">Column `' . $column . '` didn\'t exist. </span> ';
					$datatype = $type[$column]['sql'];
					$sqlAdd = "ALTER TABLE `$section` ADD $column $datatype";
					if ( $db->query($sqlAdd) ) { 					// Success
						echo '<span class="comment">The system has created it just now. ';
						echo 'MySQL query: $sqlAdd = "' . $sqlAdd . '"</span><br>';
					} 
					else { 
						echo '<span class="uh">Tried to add a column, but something went wrong. <br></span> '; 
						echo '<span class="comment">MySQL query: $sqlAdd = "' . $sqlAdd . '"</span><br>';
					}
				}
			}
		}
	}
}
?>