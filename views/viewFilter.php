<?php
// an item represented with minimal information, a link to its detail view, and a click element to remove
class viewFilter {
	function __construct($filter) { 
		global $uploaddir;
		
		$this->html = '<div class="shortie filter">';
		
		// if a photo
		if ($filter->section == 'photos') $this->html .= '
			<a href="/system/pages/itemview.php?section=' . $shortie->section . '&id=' . $shortie->id . '" target="_blank" >
				<img src="' . $uploaddir . $shortie->short . ' "> 
			</a>' . PHP_EOL; // no lazy loading image here, because shorties are lazy loading anyway
		
		// if anything else
		else switch ($filter['filter_id']) {
			case 'any': 
				$this->html .= '<span style="font-weight:600;">any ' . type($filter['filter_section']) . '</span>';
				break; 
				
			case 'none':
				$this->html .= '<span style="font-weight:600;">no ' . type($filter['filter_section']) . ' at all</span>';
				break;
				
			default:
				$this->html .= '
				<a class="nowrap" href="/system/pages/itemview.php?section=' . $filter['filter_section'] . '&id=' . $filter['filter_id'] . '" target="_blank" >'
				. type($filter['filter_section']) . ' <span style="font-weight:600">' . $filter['filter_name'] . 
				'</span></a>' . PHP_EOL;
		}
		
		// click element to AJAX remove filter
		$this->html .= '&nbsp;<span class="remove" onclick="
			var action = {}; 
			action.spot = this;
			action.data = { section:section, key: \'' . $filter['key'] . '\' };
			action.action = \'unfilter\';
			ajax(action);"
			>&times;</span>';
		
		$this->html .= '</div>';
	}
}
?>