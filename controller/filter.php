<?php 

// adds an item to the array of filtering items in $_SESSION

class filter {
	function __construct($data) { 

		$this->data = $data;
		
		$_SESSION['filters'][$data['section']][] = array( 
			'filter_section' => $data['filter_section'], 
			'filter_id' => $data['filter_id'], 
			'filter_name' => $data['filter_name'] 
		);
		
		// get the index of the filter just added, so that js can update accordingly
		$this->key = end(array_keys($_SESSION['filters'][$data['section']]));
		$this->data['key'] = $this->key;
	}
}
?>