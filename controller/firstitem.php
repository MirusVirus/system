<?php

/* Makes a database record of the no 1 item */

require_once ('../config/config.php');

$id = $_POST['id']; 
$section = $_POST['section']; 
$filter_section = $_POST['filter_section'];
$filter_id = $_POST['filter_id'];
$filter_name = $_POST['filter_name'];
	
$sql = "UPDATE ranks SET section = '$section', section_id = '$id' WHERE id = 1 ";
// $sql = "INSERT INTO ranks ( name, section ) VALUES ( 'test', 'testinger' )";
$db->query($sql);
		
header('Location: /system/pages/sectionview.php?section=' . $section );

/* Debug	
echo '<pre>'; 
print_r ($_POST); 
echo '<br>'; 
echo '$section: ' . $section . '<br>'; 
print_r ($sectionconfig[$section]); 
echo '<br>$sql: ' . $sql; 
echo '<br>$action: ' . $action; 
echo '</pre>'; 
*/

?>	
