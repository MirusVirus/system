<?php 
	session_start();
	$page = 'itemview'; 
	$id=$_GET['id'];
	$section = $_GET['section'];
	$action = 'viewItem'; 
	$view = $_GET['view'];
	
	require ($_SERVER['DOCUMENT_ROOT'] . '/system/documenthead.php');  // includes <body> tag as well as jquery, sorttable.js, datepicker.js
	
	$item = new item($sectiondata, $id); 
	// debug($item);
	//debug($sectiondata); 
	
	// Headline
	echo '<div class="startmsg"><h2>Details of '.type($item->section).' '.$item->id.'</h2>';
	echo '<a href="/system/pages/itemview.php?section='.$section.'&id='.$id.'&view=table"><button'.(($view == 'table')? ' style="color:yellow;"' : '').'>Table view</button></a>'.PHP_EOL;
	echo '<a href="/system/pages/itemview.php?section='.$section.'&id='.$id.'&view=gallery"><button' . (($view == 'gallery')? ' style="color:yellow;"' : '') . '>Gallery view</button></a>'.PHP_EOL;
	echo '<a href="/system/pages/itemview.php?section='.$section.'&id='.$id.'&view=graph1"><button' . (($view == 'graph1')? ' style="color:yellow;"' : '') . '>Graph view 1</button></a>'.PHP_EOL;
	echo '<a href="/system/pages/itemview.php?section='.$section.'&id='.$id.'&view=graph2"><button' . (($view == 'graph2')? ' style="color:yellow;"' : '') . '>Graph view 2</button></a>'.PHP_EOL;
	echo '<h4><a href="/system/pages/sectionview.php?section='.$item->section.'">Back to overview</a></h4></div>'.PHP_EOL;
	
	// Different viewing options
	switch($view) {
		case 'gallery': echo (new viewGallery(new maplings($item->section, $item->id, 'photos')))->html; break;
		case 'graph1' : echo (new viewGraph1($item))->html;  break; 
		case 'graph2' : echo (new viewGraph2($item))->html;  break; 
		default		  : 
			
			// Section items themselves in a table
			echo (new viewDetails($item))->html; 
			
			// optionally: analyze mapling sections, as defined in $sectionconfig
			if ($sectionconfig[$section]['analysis']) {
				foreach ( $sectionconfig[$section]['analysis'] as $analyzingsection ) {
					$analysis = new analysis($analyzingsection, $item);
					$analysis->analyzeTimeline();
					echo (new viewAnalysis($analysis))->html;
					// debug($analysis); 				
				}
			}
			
			// optionally: table view of selected maplings
			foreach ( $sectionconfig[$section]['mapdetails'] as $mapdetail) {
				$mapsectiondata = new sectiondata($mapdetail); 
				$maplings = new maplings($section, $id, $mapdetail); 
				$maplings->getFullItems(); 
				// debug($maplings); 
				echo (new viewTablehead($mapsectiondata))->html; 
				foreach ($maplings->items as $item) echo (new viewTablerow($mapsectiondata, $item))->html; // 
				echo (new viewTablefoot())->html; 
			}
			
			break; 
	}

?>

</body>
</html>