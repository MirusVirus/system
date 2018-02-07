<?php
// a tag with click element to remove
class viewTag {
	function __construct($section, $id, $tag) { 
		$this->html = '<div class="shortie">';
		$this->html .= $tag;
		
		// click element
		$this->html .= '&nbsp;<span class="remove" onclick="
			var action = {}; 
			action.spot = this;
			action.data = {
				section: \'' . $section . '\', 
				id: ' . $id . ', 
				tag: \'' . $tag . '\'
			};
			action.action = \'untag\';
			ajax(action);"
			>&times;</span>';
			
		$this->html .= '</div>';
	}
}
?>