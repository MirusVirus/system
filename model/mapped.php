<?php 

// items mapped to an item, represented as shortie object, structured in sections

class mapped { 																				
	function __construct($section, $id, $mapped_section) {
		global $db, $sectionconfig; 
		$short = $sectionconfig[$mapped_section]['short'][0];
		
		// get data from db
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		$sql = "
			SELECT maps.id AS link_id, maps.mapped_id AS id, $mapped_section.$short AS short
			FROM maps 
			LEFT JOIN $mapped_section ON maps.mapped_id = $mapped_section.id
			WHERE mapped_section = '$mapped_section' 
			AND mapling_section = '$section'
			AND mapling_id = $id  
			"; 
		$result = $db->query($sql); 
		
		// create shortie objects from results
		if ( mysqli_num_rows($result) > 0 ) {
			while ( $row = $result->fetch_assoc() ) {
				$this->items[] = new shortie($mapped_section, $row['id'], $row['short'], $row['link_id']); 
			}
		}
	}
}
?>