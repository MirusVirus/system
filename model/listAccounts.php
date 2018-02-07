<?php

// Creates an index of accounts, ordered by category
// var $purpose: what this index is meant for, e.g. timesheet. 
// If no purpose defined, all accounts will be selected.

class listAccounts {
	function __construct($purpose){
		global $db;
		$this->purpose = $purpose;
		
		// get data
		$sql = "SELECT * FROM accounts " . ( $this->purpose ?  "WHERE $purpose = 1" : "SELECT * FROM accounts" ) . " ORDER BY code"; 
		$result = $db->query($sql); 
		$this->accounts = $result->fetch_all(MYSQLI_ASSOC);
		
		$this->categories = array();
		foreach ($this->accounts as $account) {
    		$categories[] = $account['cat'];
		}
		
		$categories = array_unique($categories);
		
		foreach ($categories as $category) {
			$this->categories[$category] = array(); 
		}
		
		foreach ($this->categories as $category => &$accounts ) {
			foreach ($this->accounts as $account ) {
				if ($account['cat'] == $category ) {
					$accounts[] = array( 'name' => $account['name'], 'code' => $account['code'], 'descr' => $account['descr'] );
				}
			}
		}
		
	}
}
?>