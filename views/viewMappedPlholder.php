<?php
// A placeholder for items from other sections this item is mapped to. 
// Content will be added "lazy-loading" with AJAX.

class viewMappedPlholder {
	function __construct($sectiondata, $item) {
		
		global $sectionconfig, $action; 
		
		$this->html = '<td>'; 
		
		// If viewing this in a big table, we want a to control tr height
		if ($action != 'viewItem') $this->html.= '<div class="tr_height">'; 
		
		// Now we go by each section that this item can possibly be mapped to
		foreach ($sectiondata->mappable_sections as $mapped_section) {
			$this->html.= '<div>';
			$this->html.= '<span class="comment">' . $mapped_section . ':</span>';
			$this->html.= '<div class="lazyMapped" item_section="' . $sectiondata->section . '" item_id="'.$item->id.'" mapped_section="'.$mapped_section.'">';
			$this->html.= '<img src="/system/img/loader.svg">'; 							// This will be replaced with lazy content
			$this->html.= '</div>';
			$this->html.= (new viewButton('map1', $item->id, $mapped_section))->html;		// AJAX add more maps
			$this->html.= '</div>';
		}
		
		if ($action != 'viewItem') $this->html.= '</div>'; 
		$this->html.= '</td>'. PHP_EOL; 
	}
}
?>