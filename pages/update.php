<?php 
	session_start();
	$action = 'update';
	$id=$_GET['id'];
	$section = $_GET['section'];
	
	require ($_SERVER['DOCUMENT_ROOT'] . '/system/documenthead.php');  // includes <body> tag as well as jquery, sorttable.js, datepicker.js
	
	$item = new item($sectiondata, $id); 
	// debug($sectiondata);  
	// debug($item);
	
	require ($_SERVER['DOCUMENT_ROOT'] . '/system/views/input.php');;
?>

</body>
</html>