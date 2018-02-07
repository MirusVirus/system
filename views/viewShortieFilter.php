<?php
// adds click behavior to viewShortie
// click removes this item from filters

class viewShortieFilter extends viewShortie {
	function __construct($shortie) { 
		parent::__construct($shortie);
		
		// click element to AJAX remove mapping
		$this->html .= '&nbsp;<span class="remove" onclick="
			var action = {}; 
			action.spot = this;
			action.data = {map_id: ' . $shortie->link . '};
			action.action = \'unmap\';
			ajax(action);"
			>&times;</span>';
		
		// close the div that was opened in parent class
		$this->html .= '</div>';
	}
}
?>