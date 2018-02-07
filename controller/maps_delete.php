<?php
	session_start();
	require_once ($_SERVER['DOCUMENT_ROOT'] . '/system/autoload.php'); // contains db access and definitions for dynamic queries
	
	$id = $_POST['id'];
	$mapped_section = $_POST['mapped_section'];
	$mapling_section = $_POST['mapling_section'];
	$mapped_id = $_POST['mapped_id'];
	
	$stmt = $db->prepare("DELETE FROM maps WHERE id = ?");
	$stmt->bind_param("s", $id);
	$stmt->execute();
	$stmt->close();
	
	$message = 'Deleted link ' . $id . ' from ' . type($mapling_section) . ' to ' . type($mapped_section) . ' ' . $mapped_id;
	$_SESSION['message'][] = $message;
	
	header ('location: /system/pages/mapview.php?mapped_section=' . $mapped_section . '&mapped_id=' . $mapped_id . '&mapling_section=' . $mapling_section   );
	
?>


