<?php 

// Outputs search results

class viewSearch {
	function __construct($search ) { 
		
		// Display overall result count
		$this->html = '<p><strong>Found ' . $search->resultcount . ' results.</strong></p>'; 
		
		// Display results per section
		foreach ($search->results as $section => $sectionresults ) {
			$this->html.= '<p><strong>Found in ' . $section . ':</strong></br>';
			foreach ($sectionresults as $sectionresult) {
				$this->html.= '<a href="/system/pages/itemview.php?section=' . $section . '&id=' . $sectionresult['id'] . '" target="_blank">';
				$this->html.= $sectionresult['name']; 
				$this->html.= '</a>'; 
				$this->html.= '<span style="font-size:75%;">&emsp;Matching field: ' . $sectionresult['match'] . '</span></br>';
			}
			$this->html.= '</p>';
		}
		
		$this->html.= PHP_EOL;
	}
}