<?php

// Creates an overwiew of timesheets, that are either missing or completed.

class timesheets {
	function __construct($teammate){
		global $db, $timestart;
				
		// get data
		$this->teammate = new item('teammates', $_SESSION['login']['teammate']);

		$this->analysis = new analysis($this->teammate, 'hours');
		$this->analysis->analyzeTimeline();
		$this->analysis->checkWeeks();
		
	}
}
?>