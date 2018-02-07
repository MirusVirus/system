<?php 

class search_old {
	function __construct($string, $section) {
		global $sectionconfig, $sections, $db; 
		$this->search = explode(strtolower($string));
		if ($section == 'main') $this->sections = $_SESSION['login']['perm_sections']; 	// if searching from main, search in all sections allowed for this user
		else $this->sections[] = $section; 													// specified section
		foreach ($this->sections as $section) {
			foreach ( $sectionconfig[$section]['columns'] as $column ) {
				$matches[] = '(LOWER(' . $column . ') LIKE ' . '\'%' . $this->search. '%\')'; 
			}
			$match = implode($matches, ' OR ');
			$sql = "SELECT id, name from `$section` WHERE $match";
			$this->sql[] = $sql;
			$result = $db->query($sql); 
			if ( !$result ) $this->result[$section] = 'Nothing found in ' . $section;
			else { $this->result[$section] = $result->fetch_all(MYSQLI_ASSOC);}
		}
	}
}

class search {
	function __construct($string, $section) {
		global $sectionconfig, $sections, $db; 
		$this->search = strtolower($string);
		if ($section == 'index') $this->sections = $_SESSION['login']['perm_sections']; 	// if searching from main, search in all sections allowed for this user
		else $this->sections[] = $section; // specified section
		$this->resultcount = 0;
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		foreach ($this->sections as $section) {
			foreach ( $sectionconfig[$section]['columns'] as $column ) { 					// search each column separately so we know which column contains the match
				$sql = "SELECT `$section`.*,
					   	MATCH ($column) AGAINST ('$this->search') AS relevance
						FROM `$section`
						WHERE MATCH ($column) AGAINST ('$this->search' IN NATURAL LANGUAGE MODE)
						ORDER BY relevance DESC";
				
				$this->sql[] = $sql;
				$result = $db->query($sql); 
				if ( $result ) {
					$count = mysqli_num_rows($result); 
					if ( $count > 0 ) {
						$this->resultcount += $count;
						$colresults = $result->fetch_all(MYSQLI_ASSOC); 
						foreach ($colresults as &$colresult) { $colresult['match'] = $column; } // add information where the match was found
						$this->result[$section] = ($this->result[$section])? array_merge($colresults, $this->result[$section]) : $colresults; // add matches from each column to section results
					}
				}
			}
			if ($this->result[$section]) usort($this->result[$section], function($a, $b) { return $a['id'] - $b['id']; }); // sort by id
		}		
	}
}

class searchexact {
	function __construct($string, $section) {
		global $sectionconfig, $sections, $db; 
		$this->search = strtolower($string);
		if ($section == 'main') $this->sections = $_SESSION['login']['perm_sections']; 	// if searching from main, search in all sections allowed for this user
		else $this->sections[] = $section; // specified section
		$this->resultcount = 0;
		foreach ($this->sections as $section) {
			foreach ( $sectionconfig[$section]['columns'] as $column ) { 					// search each column separately so we know which column contains the match
				$sql = "SELECT id, name from `$section` WHERE $column LIKE '%$this->search%'";
				// $this->sql[] = $sql;
				$result = $db->query($sql); 
				if ( $result ) {
					$count = mysqli_num_rows($result); 
					if ( $count > 0 ) {
						$this->resultcount += $count;
						$colresults = $result->fetch_all(MYSQLI_ASSOC); 
						foreach ($colresults as &$colresult) { $colresult['match'] = $column; } // add information where the match was found
						$this->result[$section] = ($this->result[$section])? array_merge($colresults, $this->result[$section]) : $colresults; // add matches from each column to section results
					}
				}
			}
			if ($this->result[$section]) usort($this->result[$section], function($a, $b) { return $a['id'] - $b['id']; }); // sort by id
		}		
	}
}

class searchexact_old {
	function __construct($string, $section) {
		global $sectionconfig, $sections, $db; 
		$this->search = strtolower($string);
		if ($section == 'main') $this->sections = $_SESSION['login']['perm_sections']; 	// if searching from main, search in all sections allowed for this user
		else $this->sections[] = $section; 													// specified section
		foreach ($this->sections as $section) {
			foreach ( $sectionconfig[$section]['columns'] as $column ) {
				$matches[] = '(LOWER(' . $column . ') LIKE ' . '\'%' . $this->search. '%\')'; 
			}
			$match = implode($matches, ' OR ');
			$sql = "SELECT id, name from `$section` WHERE $match";
			$this->sql[] = $sql;
			$result = $db->query($sql); 
			if ( $result ) {
				$count = mysqli_num_rows($result); 
				if ( $count > 0 ) {
					$this->result[$section] = $result->fetch_all(MYSQLI_ASSOC);
				}
			}
			$matches = array();																// empty before the next loop
		}		
	}
}
?>