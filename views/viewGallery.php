<?php

// display all photos linked to an item

class viewGallery {
	function __construct($maplings) {
		global $uploaddir;
		
		// if no photos are linked, there's nothing to display
		if (count($maplings->items) < 1) $this->html = '<h3>There are no photos linked to this item.</h3>';
		
		else {
		
			// Include featherlight scripts and css
			$this->html.= '<script src="/system/res/js/featherlight.min.js" type="text/javascript" charset="utf-8"></script>';
			$this->html.= '<script src="/system/res/js/featherlight.gallery.min.js" type="text/javascript" charset="utf-8"></script>';
			$this->html.= '<link href="/system/res/css/featherlight.css" rel="stylesheet" type="text/css">';
			
			// featherlight makes a gallery out of everything inside this section
			$this->html.= '<section data-featherlight-gallery data-featherlight-filter="a">' . PHP_EOL; 
			
			// Loop through images and display
			foreach ($maplings->items as $mapling) {
				$this->html.= '<a href="' . $uploaddir . 'photo_' . $mapling->id . '.jpg">';
				// Featherlight produces a lightbox from link
				$this->html.= '<img src="' . $uploaddir . $mapling->short . '" class="galleryimg">'; 
				$this->html.= '</a>';
			}
		
		}
		
		$this->html.= '</section>';
	}
}