<?php 

// updates user data with each login

class userLog {
	function __construct($id) {
		global $db;
		
		$this->id = $id; 
		
		$this->sql = "UPDATE `users` SET last_login = NOW(), login_count = login_count +1 WHERE id = $this->id";
		$db->query($this->sql); 
		$this->db = $db; 
	}
}
?>