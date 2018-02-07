<?php 

function sql($section, $column, $id) { // returns a single value from the db by id 
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	global $db; 
	$sql = "SELECT `$column` FROM `$section` WHERE id = $id";
	$result = $db->query($sql);
	$row = mysqli_fetch_assoc($result); 
	return $row[$column]; 
}

function sqlbyname($table, $column, $name) { // returns a single value from the db by id 
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	global $db; 
	$sql = "SELECT `$column` FROM `$table` WHERE name = '$name'";
	$result = $db->query($sql);
	$row = mysqli_fetch_assoc($result); 
	return $row[$column]; 
}

function confirm($section, $id) {
	global $db;
	// delete the entry itself
 	$sql = "UPDATE `$section` SET status = 'confirmed' WHERE id = $id";
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	$result = $db->query($sql);
	if ( $result !== false || $result !== "" ) { 
		$message[] = 'Confirmed ' .type($section). ' ' .$id;
	}
	return $message; 
}



function getMaplings($section, $id, $mapling_section) {
	global $db;
	$sql = "
		SELECT id AS link, mapling_id 
		FROM maps 
		WHERE mapped_section = '$section' 
		AND mapped_id = $id 
		AND mapling_section = '$mapling_section'
		ORDER BY rank ASC";
	$result = $db->query($sql); 
	if ( mysqli_num_rows($result) > 0 ) $maplings = $result->fetch_all(MYSQLI_ASSOC); 
	return $maplings;
}
	
function getMapped($section, $id, $mapped_section) {
	global $db;
	$sql = "
		SELECT id AS link_id, mapped_id 
		FROM maps 
		WHERE mapped_section = '$mapped_section' 
		AND mapling_section = '$section' 
		AND mapling_id = $id 
		ORDER BY rank ASC";
	$result = $db->query($sql); 
	if ( mysqli_num_rows($result) > 0 ) { // if children found, return array of ids and rank
		$mapped = $result->fetch_all(MYSQLI_ASSOC); 
	}
	return $mapped;
}

function getTags($section) { // unique single tags used for an entire section
	global $db;
	// mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	$sql = "SELECT tags FROM $section GROUP BY tags ORDER BY tags ASC"; 
	if ($result = $db->query($sql)) { 
		$count = mysqli_num_rows($result); 
		if ($count > 0) {
			while ($row = $result->fetch_assoc()) {
				if ($row['tags'] !='') $tags = explode(' ', $row['tags']); // explode string into single tags
				foreach ($tags as $tag) { if ( !in_array($tag, array('', ' ', '  ' ))) $alltags[] = $tag; } // filter out empty tags
			}
		}
		return array_unique($alltags); // remove duplicates
	}
}

function checkRank($section, $id, $rank) {
	// check whether another item has the same rank
	global $db;
	$sql="SELECT * from `$section` WHERE id != '$id' 
		AND rank = '$rank'";
	$result = $db->query($sql); 
	$count = mysqli_num_rows($result);
	if ( $count > 0 ) {
		$row = mysqli_fetch_assoc($result); 
		return $row['id']; // the id of the competing item
	}
}

function checkMapRank($mapped_section, $mapped_id, $mapling_section, $mapling_id, $rank) {
	// check whether a mapling with same rank exists
	global $db;
	$sql="
		SELECT * from maps 
		WHERE mapped_section = '$mapped_section' 
		AND mapped_id = '$mapped_id' 
		AND mapling_section = '$mapling_section' 
		AND mapling_id != '$mapling_id' 
		AND rank = '$rank'";
	$result = $db->query($sql); 
	$count = mysqli_num_rows($result);
	if ( $count > 0 ) {
		$row = mysqli_fetch_assoc($result); 
		return $row['id']; // the maps id of the competing mapling
	}
}

function changeRank($section, $id, $newrank) {
	global $db;
	$stmt = $db->prepare(" UPDATE `$section` SET rank = ? WHERE id = ? " );
	$stmt->bind_param("ss", $newrank, $id );
	$stmt->execute();
	$stmt->close();
}

function changeMapRank($id, $newrank) {
	global $db;
	$stmt = $db->prepare(" UPDATE `maps` SET rank = ? WHERE id = ? " );
	$stmt->bind_param("ss", $newrank, $id );
	$stmt->execute();
	$stmt->close();
	return sql('maps', 'mapling_id', $id);
}

function tag($section, $id, $tag) {
	global $db;
	$sql = "UPDATE `$section` SET tags = CONCAT('$tag ', tags) WHERE id = $id";
	if ( $result = $db->query($sql)) return 'Tagged ' . type($section) . ' ' . sql($section, 'name', $id) . ' "' . $tag . '"';
}

function untag($section, $id, $tag) {
	global $db;
	$sql = "UPDATE `$section` SET tags = REPLACE(tags, '$tag', '') WHERE id = $id"; 
	if ( $result = $db->query($sql)) return 'Removed tag ' . $tag . ' from ' . type($section) . ' ' . sql($section, 'name', $id);
}

function mapling_sections($section) {
	/* Checks in $sectionconfig.php which $othersections are allowed to be mapped to this $section. */
	global $sectionconfig; 
	global $sections;
	$othersections = array_diff( $sections, array($section)); // the other sections 
	$mapling_sections = $othersections; 
	foreach ($othersections as $othersection) {
		if (!in_array ($section, $sectionconfig[$othersection]['map'])) {
			$mapling_sections = array_diff($mapling_sections, array($othersection)); 
		}
	}
	return $mapling_sections; 
}

function getFirstitem() {
	global $db;
	$sql = "SELECT * FROM ranks WHERE `name` = 'firstitem' ";
	$result = $db->query($sql); 
	$row = $result->fetch_assoc(); 
	return array ( 'section' => $row['section'], 'id' => $row['section_id'] );
}

function getFirstMapped($mapped_section, $mapling_section, $mapling_id) {
	global $db;
	$sql = "
		SELECT mapped_id 
		FROM maps 
		WHERE mapped_section = '$mapped_section' 
		AND mapling_section = '$mapling_section'
		AND mapling_id = '$mapling_id' 
		ORDER BY rank ASC
		LIMIT 1";
	$result = $db->query($sql); 
	$row = $result->fetch_assoc(); 
	return $row['mapped_id'];
}

function unmap($maps_id) {
	global $db;
	$sql = "DELETE FROM maps WHERE id = $maps_id"; 
	$result = $db->query($sql); 
	if ($result) return 'Deleted link ' . $maps_id; 
}
	

function getFirstPreview($section, $id) { // looks for mapped photos and returns photo path with highest map rank
	global $db; 
	global $path_photos;
	$sql = "SELECT mapling_id FROM maps WHERE mapped_section = '$section' AND mapped_id = '$id' AND mapling_section = 'photos' ORDER BY rank LIMIT 1"; 
	$result = $db->query($sql); 
	$row = $result->fetch_assoc(); 
	if ( mysqli_num_rows($result) > 0 ) {
		return $path_photos . sql('photos', 'preview', $row['mapling_id']);
	}
}

function checkTable ($section) {
/* Checks whether a table exists, if not, creates one including columns. */	
	global $db, $sectionconfig, $type; 							// From config.php
	$sql = "SELECT 1 FROM `$section` LIMIT 1";
	$result = $db->query($sql); 
	
	if ( !$result )  new table($section); 						// No table here? Let's create it
	
	else { 														// Table is already there, so let's check for missing columns
		$columns = $sectionconfig[$section]['columns']; 
		array_push ( $columns, 'updated'); 						// `updated` must be in each table
		foreach ( $columns as $column ) {
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

function getUnmapped($section) { // finds items that are no maplings, i.e. not mapped to any other sections
	global $db; 
	$sql = "SELECT id, name FROM `$section` ORDER BY id"; 
	$result = $db->query($sql); 
	$sectionitems = $result->fetch_all(MYSQLI_ASSOC); 
	foreach ( $sectionitems as $key => $value ) {
		$id = $value['id'];
		$sql2 = "SELECT * FROM maps WHERE mapling_id = '$id' ";
		$result2 = $db->query($sql2); 
		if ( mysqli_num_rows($result2) > 0 ) {
			unset($sectionitems[$key]); 
		}
	}
	return $sectionitems;		
}
?>