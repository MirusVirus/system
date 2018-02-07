<?php 

// Outputs a button. Should serve all needs

class viewButton {
	function __construct($action, $id = null, $othersection = null, $otherid = null) { 
		global $section; 
		global $firstitem;
		global $texts;
			
		switch ($action) {
			
			case 'confirm': 												// creates an item in another section and copies columns with matching name
			$this->html = '<button onclick= "
				var data = {
					section: \'' . $section . '\',
					id: ' . $id . '
				}; 
				action = new Action(this, \'' . $action . '\', data);
				exec(action); 
				">' . $texts[$action][$_SESSION['lang']] . '</button>';
			break; 
			
			case 'copy': 													// creates an item in another section and copies columns with matching name
			$this->html = '<button onclick= "
				var data = {
					section: \'' . $section . '\',
					id: ' . $id . '
				}; 
				action = new Action(this, \'' . $action . '\', data);
				exec(action); 
				">' . $texts[$action][$_SESSION['lang']] . '</button>';
			break; 
			
			case 'delete': 													// deletes item and associated links and files
			$this->html = '<button onclick= "
				data = {
					section: \'' . $section . '\',
					id: ' . $id . '
				}; 
				action = new Action(this, \'' . $action . '\', data);
				exec();
				">' . $texts[$action][$_SESSION['lang']] . '</button>';
			break; 
			
			case 'duplicate': 												// create an identical copy of this item in the same section
			$this->html= '<button onclick= "
				var data = {
					section: \'' . $section . '\',
					id: ' . $id . '
				}; 
				action = new Action(this, \'' . $action . '\', data);
				exec(action); 
				">' . $texts[$action][$_SESSION['lang']] . '</button>';
			break; 
						
			case 'firstitem': 												// display if this is the first item, otherwise have button to make it the first item
			if ( $id == $firstitem['id'] && $section == $firstitem['section'] ) { $this->html = '<span class="uoh">No. 1</span>'; }
			else { 
				$this->html = '<form method="post" action="/system/controller/firstitem.php">'; 
				$this->html.= '<input type="hidden" name="id" value="' . $id . '">';
				$this->html.= '<input type="hidden" name="section" value="' . $section . '">';
				$this->html.= '<input type="submit" value="' . $texts[$action][$_SESSION['lang']] . '"></form>' ;
			}
			break; 
			
			case 'graph': 												// display if this is the first item, otherwise have button to make it the first item
			$this->html = '<a href="/system/pages/graphview.php?section=' . $section . '&id=' . $id . '" target="_blank">';
			$this->html.= '<button>' . $texts[$action][$_SESSION['lang']] . '</button></a>';
			break; 
			
			case 'manageMaps': 												// to manage mapped items ("maplings"): Change their order and delete mappings
			$this->html = '<a href="/system/pages/mapview.php?mapped_section='.$section.'&mapped_id='.$id.'&mapling_section='.$othersection.'">';
			$this->html.= '<button>' . $texts[$action][$_SESSION['lang']] . $othersection.'</button></a>';
			break;
			
			
			case 'map1': 													// this is the mapling and will be mapped to another section
			$this->html = '<button onclick= "
				var table = getAncestorByTagName(this, \'table\'); 
				var section = table.getAttribute(\'section\');
				var data = {
					section: section,
					id: ' . $id . ',
					mapped_section: \'' . $othersection . '\', 
					rank: 1
				}; 
				action = new Action(this, \'' . $action . '\', data);
				exec(action); 
				">' . $texts[$action][$_SESSION['lang']] . $othersection . '</button>';
			break; 
			
			case 'map2': 													// a mapling from another section will be mapped to this
			$this->html = '<button onclick= "
			console.log(this); 	
				var data = {
					section: \'' . $section . '\',
					id: ' . $id . ',
					mapling_section: \'' . $othersection . '\', 
					rank: 1
				}; 
				action = new Action(this, \'' . $action . '\', data);
				exec(action); 
				">' . $texts[$action][$_SESSION['lang']] . $othersection . '</button>';
			break; 
			
			case 'print': 													// a mapling from another section will be mapped to this
			$this->html = '<button onclick= "
			console.log(this); 	
				var data = {
					section: \'' . $section . '\',
					id: ' . $id . ',
				}; 
				action = new Action(this, \'' . $action . '\', data);
				exec(action); 
				">' . $texts[$action][$_SESSION['lang']] . '</button>';
			break; 
			
			case 'save': 													// input form
			$this->html = '<input type="submit" value="' . $texts[$action][$_SESSION['lang']] . '</button>';
			break; 
			
			case 'search': 													// 
			$this->html = '<button onclick= "
				var data = {
					section: \'' . $section . '\'
				}; 
				action = new Action(this, \'' . $action . '\', data);
				exec(action); 
				">' . $texts[$action][$_SESSION['lang']] . '</button>';
			break;  
			
			case 'tag': 													// update the information of an item
			$this->html = '<button onclick = "
				var action = {}; 
				action.spot = this;
				action.data = {
					section: \'' . $section . '\',
					id: ' . $id . ', 
					tag: \'' . $othersection . '\'
				};
				action.action = \'' . $action . '\';
				ajax(action);
				">' . $othersection . '</button>';
			break; 
			
			case 'unfilterAll': 												// remove all filters
			$this->html = '<button onclick = "
				unfilterAll(); 
				">' . $texts[$action][$_SESSION['lang']] . '</button>';
			break; 
			
			case 'update': 													// update the information of an item
			$this->html = '<a href="/system/pages/update.php?section=' . $section . '&id=' . $id . '">';
			$this->html.= '<button>' . $texts[$action][$_SESSION['lang']] . '</button></a>';
			break; 
			
			case 'updateMapling': 											// update the information of a mapling
			$this->html = '<a href="/system/update.php?section=' . $othersection . '&id=' . $otherid . '"><button>Update</button></a>';
			break; 
			
			case 'urlphoto': 												// create a new photo from url and map it to this
			$this->html = '<button onclick = "
				var table = getAncestorByTagName(this, \'table\'); 
				var section = table.getAttribute(\'section\');
				var data = {
					section: section,
					id: ' . $id . ',
					mapped_section: \'photos\', 
					rank: 1
				}; 
				action = new Action(this, \'' . $action . '\', data);
				exec(action); 
				">' . $texts[$action][$_SESSION['lang']] . '</button>';
			break; 
			
			case 'viewDetails': 											// update the information of a mapling
			$this->html = '<a href="/system/pages/itemview.php?section=' . $section . '&id=' . $id . '&view=table">';
			$this->html.= '<button>' . $texts[$action][$_SESSION['lang']] . '</button></a>';
			break; 
			
			default: 
			$this->html = '<button>*some button*</button>';
			
		}
		$this->html.= PHP_EOL;
	}
}