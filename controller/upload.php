<?php
$file = $_FILES['uploadfile'];
 
if (!empty($file['name'])) {
    
	move_uploaded_file($file['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/img/upload_test/' .$file['name']);
}
?>