<?php
session_start();
require_once ($_SERVER['DOCUMENT_ROOT'] . '/system/autoload.php'); // contains db access and definitions for dynamic queries

$_SESSION['print'] = array(); 
$_SESSION['message'][]  = 'Removed all items from print queue';

header ('location: /system/pages/printview.php' );

?>


