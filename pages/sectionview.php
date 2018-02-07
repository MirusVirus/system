<?php 
	session_start();
	$page = 'sectionview'; 
	$section = $_GET['section'];

	require $_SERVER['DOCUMENT_ROOT'] . '/system/documenthead.php';  	// includes <body> tag as well as jquery, sorttable.js, datepicker.js
	
	// prepare data for each item
	$sectiondata->getItems(); 
	// output item list to js
	echo '<script>'; 
	echo 'var items = ' . json_encode($sectiondata->items, JSON_FORCE_OBJECT) . ';';
	// echo 'console.log(items);';
	echo '</script>';
	foreach ( $sectiondata->items as $key=>&$item) $item = new item($sectiondata, $key);
	unset($item); 														// need to get rid of the &$item reference, otherwise we get snafu in the next loop
	
	// Views:
	//debug($sectiondata);
	
	// Input fields
	if ($section == 'photos') {
		include ($_SERVER['DOCUMENT_ROOT'] . '/system/views/photos_select.php' ); 
		/* new fileupload - work in progress: 
		$viewFileupload = new viewFileupload; 
		echo $viewFileupload->html; 									// the html part
		echo $viewFileupload->script; 									// the js part
		*/
	}
	else include ($_SERVER['DOCUMENT_ROOT'] . '/system/views/input.php' ); 
	
	// optionally: analyze mapling sections, as defined in $sectionconfig
	if ($sectionconfig[$section]['analysis']) {
		foreach ( $sectionconfig[$section]['analysis'] as $analyzingsection ) {
			$analysis = new analysis($analyzingsection, $item);
			$analysis->analyzeTimeline();
			// debug($analysis); 
			echo (new viewAnalysis($analysis))->html;					
		}
	}
	
	// Filter box
	new viewFilters($sectiondata); 
	
	// Display existing
	echo (new viewTablehead($sectiondata))->html; 
	foreach ($sectiondata->items as $item) echo (new viewTablerow($sectiondata, $item))->html; // 
	echo (new viewTablefoot())->html; 

?>
 
</body>
</html>