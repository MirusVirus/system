<?php
class viewOptions {
	function __construct($item) {
		global $sectiondata, $action;
		 
		$this->html = '<td>'; 
		if ($action != 'viewItem') $this->html.= '<div class="tr_height">'; 							// table view: div to control tr height
		
		foreach ( $sectiondata->actions as $action ) $this->html.= (new viewButton($action, $item->id))->html;
		foreach ( $sectiondata->mapling_sections as $mapling_section ) $this->html.= (new viewButton('manageMaps', $item->id, $mapling_section))->html;
		
		if ($action != 'viewItem') $this->html.= '</div>';								 				// table view: div to control tr height
		
		$this->html.= '</div></td>';
	}
}
?>