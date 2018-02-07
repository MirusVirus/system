<?php

class viewFilters {
	function __construct($sectiondata) {
		
		echo '<fieldset class="half">';
		
		// Controls to set new filters
		
		echo '<legend><h3>Filters</h3></legend>'. PHP_EOL;
		echo '<h4>Show all ' . $sectiondata->section . ' linked to ...&ensp;'. PHP_EOL;
		echo '<select name="filter_section" onchange="
				if (typeof vilter !== \'undefined\') filter = undefined; 
				filter = new FilterByItem(this);
			">'. PHP_EOL;
		echo '<option value="" selected="selected" disabled>Please select</option>'. PHP_EOL;
		
		// Generate dropdown of mappable sections; js will do the rest
        foreach ($sectiondata->mappable_sections as $section) {
            echo '<option value="' . $section . '">' . type($section) . '</option>' . PHP_EOL;
        }
        
		echo '</select>&ensp; </h4>';
		
		echo '<div id="filters">'. PHP_EOL;														// Display existing filters, done by js
		
		/* filters reside no longer on server side
		if ( $_SESSION['filters'][$sectiondata->section] ) { 									// this condition prevents adding empty array keys
			foreach ( $_SESSION['filters'][$sectiondata->section] as $key => &$filter ) {
				$filter['key'] = $key;
				echo (new viewFilter($filter))->html;
			}
		}
		*/
		echo '</div>'. PHP_EOL;;
		
		// echo '<br>' . (new viewButton('applyFilters'))->html;
		echo (new viewButton('unfilterAll'))->html . '<br>'. PHP_EOL;
		
		// Buttons for test purposes
		//echo '<button onclick="console.log(items);">console.log(items)</button>'. PHP_EOL;
		// echo '<button onclick="{	console.log(filters); console.log(\'Filters: \' + Object.keys(filters).length)}">console.log(filters)</button>'. PHP_EOL;
		
		// debug ($_SESSION['filters'][$sectiondata->section]); 
		
		
		echo '<span class="comment">Filters are "or", i.e. each additional filter will show additional options.</span>';
		echo '</fieldset>';
		
		
		
		
	}
}

?>



