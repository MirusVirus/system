<?php
// Creates a table to view an item with all details. 

class viewDetails {
	function __construct($item) {
		global $sectionconfig, $type, $uploaddir, $sectiondata;
				
		// Table
		$this->html.= '<table>'; 
		$this->html.= '<tbody>';
		
		// Main columns, containing the actual item data
		foreach ( $item->columns as $column=>$value ) {
			$this->html.= '<tr>';
			// Labels
			$this->html.= '<td class="label">' . $type[$column]['label'] . '</td>';
			// Values
			$this->html.= (new viewValue($item, $column))->html;
			$this->html.= '</tr>' . PHP_EOL;
		}
		
		// Items from other sections mapped to this
		foreach ( $sectiondata->mapling_sections as $mapling_section ) {
			$this->html.= '<tr>'; 
			$this->html.= '<td class="label">Linked '. $mapling_section . '</td>'.PHP_EOL;
			$this->html.= (new viewMaplingPlholder($sectiondata, $item, $mapling_section))->html; 						// empty div to be filled with AJAX
			$this->html.= '</tr>';
		}
		
		// Items from other sections this item is mapped to
		if ($sectiondata->mappable_sections) {
			$this->html.= '<tr><td class="label">This '.type($item->section).' is linked to:</td>';
			$this->html.= (new viewMappedPlholder($sectiondata, $item))->html;											// empty div to be filled with AJAX
		}
		
		// Options
		$this->html.= '<tr><td class="label">Options</td>';
		$this->html.= (new viewOptions($item))->html; 
		
		// close table
		$this->html.= '</tbody>';
		$this->html.= '</table>';
	}
}