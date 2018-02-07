<?php
session_start();
require_once ($_SERVER['DOCUMENT_ROOT'] . '/system/autoload.php'); // contains db access and definitions for dynamic queries

$save = new saveItem($_POST);
$save->process();
$_SESSION['message'][] = $save->message;

// debug($save);


if ( !empty ( $save->data['rank'] )) { // check if other items have same rank; if so, push their ranks down 
	$competing_item = checkRank($save->section, $save->id, $save->data['rank']); 
	$rank = $save->data['rank'];
	while ( !empty ( $competing_item )) {
		$newrank = $rank + 1; 		
		changeRank($save->section, $competing_item, $newrank);
		$_SESSION['message'][] = 'Changed rank of ' . type($save->section) . ' ' . $competing_item. ' to ' . $newrank;
		$id = $competing_item;
		$rank = $newrank; 
		$competing_item = checkRank($save->section, $id, $rank);
	}
}


header('Location: /system/pages/sectionview.php?section=' . $save->section );

?>