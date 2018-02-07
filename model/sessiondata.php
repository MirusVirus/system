<?php 

// when user logs in, user-related data is stored in $_SESSION

class sessiondata { 
	function __construct($name, $id) {
		
		// Login status
		$_SESSION['login']['status'] = 'loggedin'; 
		
		// Actual user data
		$_SESSION['login']['name'] = $name;
		$_SESSION['login']['id'] = $id;
		$_SESSION['login']['role'] = sql('users', 'role', $id);
		$_SESSION['login']['teammate'] = getFirstMapped('teammates', 'users', $id); // get teammates id
		$_SESSION['login']['photo'] = getFirstPreview( 'teammates', $_SESSION['login']['teammate']); // get first preview
		$_SESSION['lang'] = sql( 'users', 'lang', $id); // get default language of this user		
		// User rights
		$perm_sections = new perm_sections(); 
		$_SESSION['login']['perm_sections'] = $perm_sections->sections; // sessions allowed to view and edit for this user
		
		// Custom pages & views (= views specific to the project and not part of the default config)
		$customviews = new customviews(); 
		$_SESSION['customviews'] = $customviews->sections;
		$custompages = new custompages(); 
		$_SESSION['custompages'] = $custompages->sections; 
		
		
	}
}
?>