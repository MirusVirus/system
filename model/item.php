<?php 
// Object with all values of an item. 
// Mapping information not contained.

class item { 																				// 
	function __construct($sectiondata, $id) {
		
		// create $sectiondata if input is only a section
		if ( !is_object($sectiondata)) $sectiondata = new sectiondata($sectiondata); 		// We assume input was a section name
		$this->section = $sectiondata->section;
		$this->id = $id; 
		global $sectionconfig, $db, $general_actions;
		$columns = $sectionconfig[$this->section]['columns'];
		
		
		// Get data from db
		$sql = "SELECT * FROM `$this->section` WHERE id=$id"; 
		$result = $db->query($sql);
		$obj=mysqli_fetch_object($result); 
		foreach ( $columns as $column ) { 													// create properties for each column
			$this->columns[$column] = $obj->$column;
		}
		if ($this->section == 'photos' ) { $this->preview = $obj->preview; } 				// preview is usually not displayed as own column 
		if (isset($this->columns['tags'])) $this->newtags = array_diff($sectiondata->tags, explode(' ', $this->columns['tags']));
		
		// additional information
		$this->actions = array_merge($sectionconfig[$section]['actions'], $general_actions); 
		$this->sorting = implode( $sectionconfig[$section]['sorting'], ', '); 				// as defined for each section in config.php. 
	}
	
	// The following is usually done with AJAX now, which uses another function, so we have double code :(
	
	function getMaplings() {
		global $db, $sectionconfig;
		foreach ( mapling_sections($this->section) as $mapling_section ) {
			$sql = "
				SELECT id AS map_id, mapling_id AS id 
				FROM maps 
				WHERE mapped_section = '$this->section' 
				AND mapped_id = $this->id 
				AND mapling_section = '$mapling_section'
				ORDER BY rank ASC";
			$result = $db->query($sql); 
			if ( mysqli_num_rows($result) > 0 ) {
				while ($row = $result->fetch_assoc()) { 
					$this->maplings[$mapling_section][] = new shortie ($mapling_section, $row['id'], $row['map_id']); 
				}
			}
		}
	}
	
	function getMapped() {
		global $sectionconfig;
		foreach ( $sectionconfig[$this->section]['map'] as $mapped_section ) {
			$this->mapped[$mapped_section] = getMapped($this->section, $this->id, $mapped_section); 
			
			// foreach ( $mappeds as $mapped ) { $this->mapped[$mapped_section][] = new shortie($mapped_section, $mapped, $link); }
		}
	}

}
?>