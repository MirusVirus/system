<?php
// Creates a tablerow to view an item with details. 

class viewTablerow {
	function __construct($sectiondata, $item) {
		global $uploaddir;
		
		// Tablerow gets id of item
		$this->html = '<tr class="itemrow" name="' . $item->id . '">'; 
		$this->html.= '<td>'; 
		$this->html.= '<span class="comment">' . $item->id . '</span><br>';
		$this->html.= '<img class="toggleTRHeight" src="/system/img/arrow_black.svg" style="width:14px; cursor:pointer;">';
		$this->html.= '</td>' . PHP_EOL;
		
		// Columns for this section as defined in config.php
		foreach ($item->columns as $column=>$value) $this->html.= (new viewValue($item, $column))->html; 
		
		// Mapped items from other sections
		foreach ( $sectiondata->mapling_sections as $mapling_section ) {
			$this->html.= (new viewMaplingPlholder($sectiondata, $item, $mapling_section))->html; // empty div to be filled with AJAX
		}
		
		// Items from this section mapped to other sections
		if ($sectiondata->mappable_sections) $this->html.= (new viewMappedPlholder($sectiondata, $item))->html;
		
		// Options
		$this->html.= (new viewOptions($item))->html; 
		
		$this->html.= '</tr>' . PHP_EOL;
	}
}
