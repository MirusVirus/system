<?php 
// Results for a string-based search in the database

class search {
	function __construct($string, $section) {
		global $sectionconfig, $sections, $db; 
		$this->string = strtolower($string);
		
		// specify the scope of search
		if ($section == 'main') $this->sections = $_SESSION['login']['perm_sections']; 	// if searching from main, search in all sections allowed for this user
		else $this->sections[] = $section; 													// specified section
		
		$this->resultcount = 0;
		
		// The actual db search
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		foreach ($this->sections as $section) {
			foreach ( $sectionconfig[$section]['columns'] as $column ) { 					// search each column separately so we know which column contains the match
				$sql ="SELECT id, name from `$section` WHERE $column LIKE '%$this->string%'";
				
				// $this->sql[] = $sql;
				$result = $db->query($sql); 
				if ( $result ) {
					$count = mysqli_num_rows($result); 
					if ( $count > 0 ) {
						$this->resultcount += $count;
						$colresults = $result->fetch_all(MYSQLI_ASSOC); 
						foreach ($colresults as &$colresult) $colresult['match'] = $column; // add information where the match was found
						$this->results[$section] = ($this->results[$section])? array_merge($colresults, $this->results[$section]) : $colresults; // add matches from each column to section results
					}
				}
			}
			if ($this->results[$section]) {
				usort(																			// sort by id
					$this->result[$section], function($a, $b) { return $a['id'] - $b['id']; }
				); 
			}
		}		
	}
}

?>