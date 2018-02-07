<?php 

	session_start();
	$action = 'update';
	$id=$_GET['id'];
	$section = $_GET['section'];
	
	require ($_SERVER['DOCUMENT_ROOT'] . '/system/documenthead.php');  // includes <body> tag as well as jquery, sorttable.js, datepicker.js
	echo file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/system/img/test2.svg");
	
	
?>



</body>
</html>