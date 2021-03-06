<?php
// an item represented with minimal information, a link to its detail view
///// NOTE - this needs a child class to add closing button and close the div
class viewShortie {
	function __construct($shortie) { 
		global $uploaddir;
		
		$this->html = '<div class="shortie">';
		
		// if a photo
		if ($shortie->section == 'photos') $this->html .= '
			<a href="/system/pages/itemview.php?section=' . $shortie->section . '&id=' . $shortie->id . '" target="_blank" >
				<img src="' . $uploaddir . $shortie->short . ' "> 
			</a>' . PHP_EOL; // no lazy loading image here, because shorties are lazy loading anyway
		
		// if anything else
		else $this->html .= '
			<a class="nowrap" href="/system/pages/itemview.php?section=' . $shortie->section . '&id=' . $shortie->id . '" target="_blank" >'
				. $shortie->short . 
			'</a>' . PHP_EOL;
		
		// click element to AJAX remove mapping: in child class
		
		// $this->html .= '</div>'; in child class
	}
}
?>