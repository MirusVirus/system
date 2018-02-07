<?php 

// create an identical copy of this item in the same section or copy intersecting columns to another section

class duplicate {
	function __construct($source_section, $id, $target_section) { 
		global $db, $sectionconfig;
		$this->source_section = $source_section; 
		$this->source_id = $id;
		$this->target_section = $target_section;
		
		// determine intersecting columns and create a string of them
		$this->source_columns = $sectionconfig[$this->source_section]['columns']; 
		if ($this->source_section == 'photos') array_push($this->source_columns, 'preview'); // because preview is not mentioned in column config
		$this->target_colums = $sectionconfig[$this->target_section]['columns']; 
		if ($this->target_section == 'photos') array_push($this->target_section, 'preview'); // because preview is not mentioned in column config
		$this->columnsarr = array_intersect($this->source_columns, $this->target_colums);
		$this->columns = implode($this->columnsarr, ', ');
		
		// do the database stuff
		$sql= "INSERT INTO `$this->target_section` ($this->columns) SELECT $this->columns FROM `$this->source_section` WHERE id = $this->source_id";
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		$result = $db->query($sql);
		if ( $result !== false || $result !== "" ) { 
			$this->target_id = $_SESSION['insert_id'] = $db->insert_id; 					// gets the id of this db insert
			$this->message[] = 'Copied '.type($this->source_section).' '.$this->source_id.' to new '.type($this->target_section). ' ' .$this->target_id; 
			$this->message[] = 'Matching fields: ' . $this->columns. '.';
			
			// check if other items have same rank; if so, push their ranks down 
			if (in_array('rank', $this->columnsarr)) { 
				$this->rank = sql($this->target_section, 'rank', $this->target_id);
				$competing_item = checkRank($this->target_section, $this->target_id, $rank); 
				while ( !empty ( $competing_item )) {
					$newrank = $rank + 1; 
					changeRank($this->target_section, $competing_item, $newrank);
					$this->message[] = 'Changed rank of '. type($this->target_section) . ' ' . $competing_item . ' to ' . $newrank;
					$id = $competing_item;
					$rank = $newrank; 
					$competing_item = checkRank($this->target_section, $id, $rank);
				}
			}
		}
		else $this->message[] = 'Problems copying ' . type($this->source_section) . ' ' . $this->source_id;
	}
	
	// to replicate all mappings, no php iteration necessary - just one sweet query :)
	function recreateLinks() {
		global $db; 
		
		// mappings to the duplicated item (item is mapping target)
		$sql = "INSERT INTO maps (mapped_section, mapped_id, mapling_section, mapling_id, rank)
				SELECT '$this->target_section', $this->target_id, mapling_section, mapling_id, rank
				FROM maps
				WHERE mapped_section = '$this->source_section' AND mapped_id = $this->source_id";
		$result = $db->query($sql);
		if ( $result !== false || $result !== "" ) $this->message[] = 'Re-created ' . $db->affected_rows . ' links';
		
		// mappings from the duplicated item (item is the mapling)
		$sql = "INSERT INTO maps (mapped_section, mapped_id, mapling_section, mapling_id, rank)
				SELECT mapped_section, mapped_id, '$this->target_section', $this->target_id, rank
				FROM maps
				WHERE mapling_section = '$this->source_section' AND mapling_id = $this->source_id";
		$result = $db->query($sql);
		if ( $result !== false || $result !== "" ) $this->message[] = 'Re-created ' . $db->affected_rows . ' links';

	}
	
	// checks whether source and copy section may be mapped to each other and if so creates a mapping
	function linkCopy() {
		global $sectionconfig; 
		
		// source can be mapped to target - source = mapling
		if ( in_array( $this->target_section, $sectionconfig[$this->source_section]['map'])) {
			new map($this->target_section, $this->target_id, $this->source_section, $this->source_id, 1);
			$this->message[] = 'Linked source item to copy.';
		}
		
		// target can be mapped to source - target = mapling
		elseif ( in_array( $this->source_section, $sectionconfig[$this->target_section]['map'])) {
			// new map($this->source_section, $this->source_id, $this->target_section, $this->target_id, 1);
			$this->message[] = 'Linked copy to source item.';
		}
		
		else $this->message[] = 'No linking allowed between source and target.';
	}
	
}



?>