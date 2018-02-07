<?php

class viewTablehead {
	function __construct($sectiondata) {
		global $type, $page, $maplings, $texts;
		 
		if ($page == 'sectionview') {
			$this->html = '<fieldset><legend><h3>' . $sectiondata->count . ' ' . $sectiondata->section . ' exist:</h3></legend>' . PHP_EOL; 
		}
		if ($page == 'itemview') {
			$this->html = '<fieldset><legend><h3>'; 
			$this->html.=  count($maplings->items) . ' linked ' . $sectiondata->section . ' :';
			$this->html.= '</h3></legend>' . PHP_EOL; 
		}
		$this->html.= '<span class="comment">Click rows to expand.&emsp; Click table head labels to sort.&emsp;';
		$this->html.= 'Predefined sorting: ' . $sectiondata->sorting . '</span>' . PHP_EOL;
		$this->html.= '<table class="sortable minmax ' . $sectiondata->section . '" ';				// minmax = expand rows on click
		$this->html.= 'section ="' . $sectiondata->section . '">' . PHP_EOL; 						// need to indicate section 'cause tables for different sections can be on one page
		$this->html.= '<thead>' . PHP_EOL;
		$this->html.= '<tr>'; 
		$this->html.= '<th>ID<br>';
		$this->html.= '<img class="toggleAllTRHeight" src="/system/img/arrow_white.svg" style="width:14px; cursor:pointer;">';
		$this->html.= '</th>';
		// Columns for this section as defined in config.php
		foreach ( $sectiondata->columns as $column ) $this->html.= '<th>' . $type[$column]['label'] . '</th>';
		// Mapped items from other sections
		foreach ( $sectiondata->mapling_sections as $mapling_section ) {
			$this->html.= '<th>' . $texts['linked'][$_SESSION['lang']] . $othersection .  $mapling_section . '</th>';
		}
		// Items from this section mapped to other sections
		if ( $sectiondata->mappable_sections ) {
			$this->html.= '<th>' . $texts['links'][$_SESSION['lang']] . '</td>';
		}
		// Options
		$this->html.= '<th>' . $texts['options'][$_SESSION['lang']] . '</th>';
		$this->html.= '</tr>' . PHP_EOL;
		$this->html.= '</thead>' . PHP_EOL;
		$this->html.= '<tbody>';
	}
}
?>