<?php
// Switches to the desired language. 
// Stores language preference in user table and $_SESSION

class lang {
	function __construct($lang) {
		global $db; 
		
		$this->lang = $lang;
		$this->id = $_SESSION['login']['id'];
		
		// Change $_SESSION
		$_SESSION['lang'] = $this->lang;
		$this->message[] = 'Changed language to ' . $this->lang;
		
		// Change entry in user table
		if ( $_SESSION['login']['status'] == 'loggedin' ){
			$sql = "UPDATE users SET lang = '$this->lang' WHERE id = $this->id";
			if($db->query($sql)) {
				$this->message[] = 'Changed language preference for user ' . $this->id . ' to ' . $this->lang;
			} 
		}
	}
}
?>