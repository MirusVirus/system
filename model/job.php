<?php 
// Prepare data to create a short view of a job (= hour & mapped)

class job {
	function __construct($id) {
		global $db, $sectionconfig;
		$this->section = 'hours'; 
		$this->id = $id;
		
		// hours columns
		$this->name = sql($this->section, 'name', $id); 
		$this->descr = sql($this->section, 'descr', $id);
		$this->cal_week = sql($this->section, 'cal_week', $id);  
		$this->amount = sql($this->section, 'amount', $id);
		
		// mapped
		$account = getFirstMapped('accounts', 'hours', $id); 
		if ($account) $this->account = new item ('accounts', $account); 
		$project = getFirstMapped('projects', 'hours', $id); 
		if ($project) $this->project = new item ('projects', $project); 
		$carpart = getFirstMapped('carparts', 'hours', $id); 
		if ($carpart) $this->carpart = new item ('carparts', $carpart); 
		$procedure = getFirstMapped('procedures', 'hours', $id); 
		if ($procedure) $this->procedure = new item ('procedures', $procedure); 
	}
}
?>