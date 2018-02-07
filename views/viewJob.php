<?php
// short depiction of a job (= hour & mapped)

class viewJob {
	function __construct($job) { 
	
		// debug($job);
		
		$this->html = '<div class="shortie">';
		
		if ( $job->project ) {												// all sorts of things to show here
			$this->html .= $job->project->columns['name'] . ', ';
			$this->html .= $job->carpart->columns['name_pl'];
			if ($job->descr) $this->html .= ', ' . $job->descr ;
		}
		else $this->html .= $job->account->columns['name'];
		$this->html .= ': ' . $job->amount .  'h';
		
		// click elements to delete and copy
		$this->html .= '&emsp;<img class="mediumicon" src="/system/img/delete.svg" onclick="
			var action = {}; 
			action.data = {
				section: \'hours\', 
				id: ' . $job->id . '
				};
			action.action = \'timesheetDelete\';
			ajax(action);"
			>';
			
		$this->html .= '&emsp;<img class="mediumicon" src="/system/img/copy-file.svg" onclick="
			var action = {}; 
			action.data = {
				section: \'hours\', 
				id: ' . $job->id . ', 
				copy_section: \'hours\', 
				recreateLinks: true
				};
			action.action = \'timesheetCopy\';
			ajax(action);"
			>';
		
		$this->html .= '</div>';
		$this->html .= '<br>';
			
	}
}
?>