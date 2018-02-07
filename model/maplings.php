<?php 
// items mapped to an item, represented as shortie object, structured in sections

class maplings { 																			
	function __construct($section, $id, $mapling_section) {
		global $db, $sectionconfig; 
		$short = $sectionconfig[$mapling_section]['short'][0];
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		$sql = "
			SELECT maps.id AS link_id, maps.mapling_id AS id, $mapling_section.$short AS short
			FROM maps 
			LEFT JOIN $mapling_section ON maps.mapling_id = $mapling_section.id
			WHERE mapling_section = '$mapling_section' 
			AND mapped_section = '$section'
			AND mapped_id = $id  
			"; 
		$result = $db->query($sql); 
		if ( mysqli_num_rows($result) > 0 ) {
		 	while ( $row = $result->fetch_assoc() ) {
		 		$this->items[] = new shortie($mapling_section, $row['id'], $row['short'], $row['link_id']); 
			}
		}
	}
	
	// if no "shortie" preview but the full item data is required, use this: 
	function getFullItems() {
		foreach ( $this->items as &$item ) {
			$item = new item( $item->section, $item->id ); 
		}
	}
}
?>