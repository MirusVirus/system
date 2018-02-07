<?php
session_start();
require_once ($_SERVER['DOCUMENT_ROOT'] . '/system/autoload.php'); // contains db access and definitions for dynamic queries

$id = $_POST['id'];
$newrank = $_POST['newrank'];
$mapped_section = $_POST['mapped_section'];
$mapped_id = $_POST['mapped_id'];
$mapling_section = $_POST['mapling_section'];
$mapling_id = $_POST['mapling_id'];

$message = array();

changeMapRank($id, $newrank);
$_SESSION['message'][] = 'Changed rank of link ' . $id . ' to ' . $newrank;

// check whether competing maplings have the same rank

$competing_map = checkMapRank($mapped_section, $mapped_id, $mapling_section, $mapling_id, $newrank);

while ( !empty ($competing_map)) {
	$_SESSION['message'][] = 'Competing link with same rank: ' . $competing_map;
	$newcompetingrank = $newrank + 1; 
	$competing_mapling = changeMapRank($competing_map, $newcompetingrank);
	$_SESSION['message'][]  = 'Changed rank of competing link to ' . $newcompetingrank;
	$newrank = $newcompetingrank; 
	$competing_map = checkMapRank($mapped_section, $mapped_id, $mapling_section, $competing_mapling, $newrank);
}

header ('location: /system/pages/mapview.php?mapling_section=' . $mapling_section . '&mapped_section=' . $mapped_section . '&mapped_id=' . $mapped_id );

?>


