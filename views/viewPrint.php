<?php
// Displays items in the print queue for the specified section. 

class viewPrint {
	function __construct($printSection) {
		$this->section = $printSection;
		global $printoptions; 
				
		// Section label
		$this->html.= '<h3>' . $this->count . ' ' . $this->section . ':</h3>';
		
		// Shortie view of ech printed item
		foreach ($_SESSION['print'][$this->section] as $key => $printItem) {
			$this->html.= (new viewPrintitem(new shortie($this->section, $printItem['id'], $printItem['short'], $key)))->html;
		}
		$this->html.= '<br>';
		
		// Output button for each print option predefined for this section
		foreach ($printoptions->print_sections[$this->section] as $printoption) {
			$this->html.= '<a href="/system/pages/outputPrint.php?output=' . $printoption['class'] . '" target="_blank">'; 
			$this->html.= '<button>Print ' . $printoption['name'] . '</button></a>';
		}
	}
}