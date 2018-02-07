<?php 
// Modal dialogue windows for AJAX actions. 
// They will NOT be created on the fly; they are all pre-loaded in documenthead.php

class viewDialog {
	function __construct($action, $section) {
		global $dynamic_dialogs;
		$dynamic = (in_array($action, $dynamic_dialogs)) ? true : false; 		// whether dynamic content needs to be added with js
																				// js will fill all <span class="jsinsert {value}"> with {value}
		
		// HTML output
		echo '<!-- viewDialog object -->' . PHP_EOL;
		echo '<div id="' . $action . '" class="modal">' . PHP_EOL; 				// invisible element covering all screen
    	echo '<div class="dialog info ' . ($dynamic? 'dynamic' : '') . '" draggable="true">' . PHP_EOL; 			// the visible, draggable dialogue box
    	
		// HEADER
		echo '<div class="dialog_head"> ';		
        echo '<span class="close">&times;</span>'; 								// Close button
		
		switch ($action) { 														// Headline, depending on action
			case 'confirm':		
				echo '<h3>Confirm ' . type($section) . ' <span class="jsinsert id"></span>?</h3>'; 
				break; 
				
			case 'copy':		
				echo '<h3>Copy '. type($section) . ' <span class="jsinsert id"></span> to another section?</h3>'; 
				break; 
				
			case 'delete':		
				echo '<h3>Delete ' . type($section) . ' <span class="jsinsert id"></span>?</h3>'; 
				break;
				
			case 'duplicate':	
				echo '<h3>This will create an identical copy of ' . type($section) . ' <span class="jsinsert id"></span>.</h3>'; 
				break;
				
			case 'map1': 		
				echo '<h3>Link ' . type($section) . ' <span class="jsinsert id"></span> to <span class="jsinsert mapped_section"></span></h3>'; 
				break;
			
			case 'map2':		
				echo '<h3>Link any of these <span class=" jsinsert mapling_section"></span> to ' . type($section) . ' <span class="jsinsert id"></span> </h3>'; 
				break;
				
			case 'search':		
				echo '<h3>Search ' . (($section == 'main')? 'all sections' : $section ) . '</h3>'; 
				break;
				
			case 'urlphoto':		
				echo '<h3>Create new photo from url and link it to this item</h3>'; 
				break;
				
			default:			echo '<h3>* undefined dialogue *</h3>';
		}
		echo '</div>' . PHP_EOL;												// End header
		
		// BODY
    	echo '<div class="dialog_body">'; 
		
		switch ($action) { 														// Non-dynamic content 1
			case 'copy':   
				echo '<input type="checkbox" id="recreateLinks">Re-create all links</input>&emsp;<input type="checkbox" id="linkCopy">Link copy to source</input><br>'. PHP_EOL; 
				break;
			
			case 'search': 
				echo '<input type="text" id="searchbox" style="margin-bottom:12px;"><br>';
				echo '<button id="searchbtn" onclick="
					var searchbox = document.getElementById(\'searchbox\'); 
					action.data.string = searchbox.value; 
					ajax(action); 
					action.dialog_dyn.innerHTML = \'\'; 
					">';
				echo 'Search</button>'; 
				break;
			
			case 'urlphoto':		
				echo '<input id="urlphotoinput" type="text" placeholder="http://...">'; 
				break;
		}
		
		if ($dynamic) echo '<div id="'.$action.'_dyn" class="resetcont"></div>'. PHP_EOL; // dynamic content
		
		switch ($action) { 														// Non-dynamic content 2
			case 'delete': 
				echo '<p class="uh">There is no undo.</p><button class="submit" onclick="ajax(action); closeModal();">Yup, delete</button>'; 
				break;
				
			case 'urlphoto':		
				echo '<button class="submit" onclick="
					action.data.url = document.getElementById(\'urlphotoinput\').value; 
					ajax(action); 
					closeModal();">
					Let\'s go</button>'; 
				break;
				
			default: if ( !$dynamic ) echo '<button class="submit" onclick="ajax(action); closeModal();">' . $action . '</button>'; // submit button if nothing else specified
		}
		
		echo PHP_EOL . '</div></div></div>' . PHP_EOL;							// close dialogue body, dialogue box, dialogue
		
		echo '<!-- end viewDialog object -->' . PHP_EOL;
	}
}