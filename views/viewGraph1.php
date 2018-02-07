<?php 

// displays a springy.js graph of the item and all direct mappings

class viewGraph1 {
	function __construct($item) {
		
		// include springy scripts
		$this->html = '<script src="/system/res/js/springy.js"></script>' . PHP_EOL;
		$this->html.= '<script src="/system/res/js/springyui.js"></script>' . PHP_EOL;
		
		// request AJAX data
		$this->html.= '<script>' . PHP_EOL;
		$this->html.= 'var data = { section: \'' . $item->section . '\', id: ' . $item->id . '};' . PHP_EOL;
		$this->html.= 'action = new Action(\'\', \'graph1\', data);' . PHP_EOL;
		$this->html.= 'ajax(action);' . PHP_EOL; 
		// Graph will be rendered once AJAX data have arrived, see 
		// functions.js > function ajax(action) & showGraph(response)
		$this->html.= '</script>' . PHP_EOL;
		
		// output the canvas
		$this->html.= '<canvas id="graph_canvas" width="1800" height="950"/>';
	}
}