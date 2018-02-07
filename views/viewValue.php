<?php
// one cell that displays a column value (i.e. no maplings, mapped, options)
class viewValue {
	function __construct($item, $column) { 
													
		global $action, $type, $uploaddir; 
		$value = $item->columns[$column];
		$editable = $type[$column]['editable'] ?: 'true';												// editable by default
		
		
		$this->html = '<td ' . ($editable? 'class="editable" ' : '') . 'column="' . $column . '" type="' . $type[$column]['type'] . '"'; 
		if ($type[$column]['type'] == 'website') $this->html.= ' value="' . $value . '"'; 						// include original db value for editing in table view
		$this->html.= '>';
		
		$this->html.= '<div '. ($action != 'viewItem' ? 'class="tr_height" ' : '') . '>'; 				// table view: div to control tr height
		
		// display values according to type set in config.php
		switch ( $type[$column]['type'] ) { 
			
			// make dates prettier
			case 'datetime': $this->html .= displaydate($value); break; 
			
			// lazy load pictures
			case 'file': 
				$this->html .= '<a class="bigimg" href="' . $uploaddir . $value . '" target="_blank" >';
				$this->html .= '<img src="/system/img/loader.svg" class="lazy" data-src="' . $uploaddir . $item->preview . ' ">';
				$this->html .= '</a>'; 
				break; 
			
			// references to ids of other sections: need to get name
			case 'option': if (!empty($value)) $this->html .= sql($type[$column]['option'], 'name', $value); break; 
			
			// tags: buttons for AJAX tagging for each tag not used so far	
			case 'tags':
				$this->html.= '<div clas="tags">'; 
				foreach ( explode(' ', $value) as $tag) { 												// chop tag string into single tags and display
					if ($tag !== '') $this->html .= (new viewTag($item->section, $item->id, $tag))->html; 
				} // chop tag string into single tags and display
				$this->html.= '</div>'; 
				$this->html.= '<div class="newtags">'; 
				$this->html.= '<span class="comment">Add tags:</span>';
				foreach ( $item->newtags as $newtag ) $this->html.= (new viewButton('tag', $item->id, $newtag))->html;
				$this->html.= '</div>'; 
				$this->html.= '<span class="comment">Add more tags via update.</span>';
				break;	
				
			// yes/no will be a checkbox of course and that checkbox should be interactive right away		
			case 'yesno': 
				$editable = 'false'; 																	// it is editable, but no icon needed, hence "false"
				$this->html.= '<input type="checkbox" ' . (($value == true)?  'checked="checked"' : '');
				$this->html.= 'var spot = this; onchange= editCheckbox(this)';
				$this->html.= '>';
				
				break;
			
			// urls made prettier and shorter			
			case 'website': 
				if ($value && substr($value, 0, 7) != 'http://' && substr($value, 0, 8) != 'https://'){ // add missing http://
					$value = 'http://' . $value; 
				}	
				if ($action == 'viewItem') {															// detail view: full view
					$this->html.= '<a href="'.$value.'" target="_blank">'.$value.'</a>';
				}
				else $this->html.= '<a href="'.$value.'" target="_blank">'.prettyHTTP($value).'</a>'; 	// table view: short view
				break;
			
			// all others - just show plain value
			default: $this->html .= $value; break; 

		}
		
		
		$this->html.= '</div>';								 											// table view: div to control tr height
		
		// edit icon if cell editable (defined in config.php: $type)
		$this->html.= ($editable == 'true'? '
				<img class="editbutton miniicon" src="/system/img/edit.svg">
				<img class="editbutton2 save miniicon" src="/system/img/ok.svg">
				<img class="editbutton2 cancel miniicon" src="/system/img/cancel.svg">
			' :'');
		
		$this->html.= '</td>' . PHP_EOL;
	}
}