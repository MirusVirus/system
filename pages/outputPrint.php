<?php 

// creates a pdf from specified items in print queue
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/system/res/php/TCPDF-master/tcpdf.php';
require_once ($_SERVER['DOCUMENT_ROOT'] . '/system/autoload.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/config/img/svg.php');

// debug($_REQUEST);

$output = $_REQUEST['output'];					// what to be printed (name of print class)

$pdf = new $output($_REQUEST); 

// debug($pdf); 

$pdf->Output();

?>


