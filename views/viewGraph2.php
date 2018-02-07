<?php 

// displays a springy.js graph of the item and all direct mappings

class viewGraph2 {
	function __construct($item) {
		
		// create svg
		$this->html = '<svg width="1200" height="800"></svg>' . PHP_EOL;

		// include D3 scripts
		$this->html.= '<script src="https://d3js.org/d3.v4.min.js"></script>' . PHP_EOL;	// library
		$this->html.= '<script src="/systen/res/js/d3_force.js' . PHP_EOL; 					// specific stuff
		
		// request AJAX data
		$this->html.= '<script>' . PHP_EOL;
		$this->html.= 'var data = { section: \'' . $item->section . '\', id: ' . $item->id . '};' . PHP_EOL;
		$this->html.= 'action = new Action(\'\', \'graph2\', data);' . PHP_EOL;
		$this->html.= 'ajax(action);' . PHP_EOL; 
		// Graph will be rendered once AJAX data have arrived, see 
		// functions.js > function ajax(action) & showGraph(response)
		$this->html.= '</script>' . PHP_EOL;
		

	}
}