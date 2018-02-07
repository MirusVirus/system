<?php 

// creates a pdf from specified items in print queue
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/system/res/php/fpdf.php';
require_once ($_SERVER['DOCUMENT_ROOT'] . '/system/autoload.php');

$pdf = new FPDF();
$pdf->AddPage();

$top = 10;
$height = 40;
$margin = 10; 

$pdf->SetAutoPageBreak(auto);

foreach ($_SESSION['print'] as $section => $items) {
	// $pdf->SetFont('Arial','B', 16, 1, 2);
	// $pdf->Cell(0, 10, ucfirst($section));
	$sectiondata = new sectiondata($section); 
	
	
	foreach ($items as &$item) {
		// we create an item object, so we have all data at hand
		$item = new item($sectiondata, $item['id']); 

		$pdf->Rect(10, $top, 190, $height);
		$pdf->SetFont('Arial', 'B', 14); 
		$pdf->SetY($top); 
		$pdf->Cell(0, 10, utf8_decode($item->columns['name'])); 
		$top += $height + $margin;
	}
}

$pdf->Output();


?>


