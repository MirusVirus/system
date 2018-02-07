<? 

switch ($_POST['installstep']) {
	
	case '': // Nothing entered or clicked; this is the very start!!
		echo '
			<h2>Welcome!</h2>
			<p>This should help to get the basics of your admin system straight. </p>
			<p>You should have copied the \'system\' folder with all contents to your root directory, <br>
			created a database, and entered database access information into config/config.php.</p>
			Ready to start?
			<form action="install.php" method="post">
			<input type="hidden" name="installstep" value="2">
			<input type="submit" value="Ready, let\'s go">
		</form>';
		break; 
		
	case '2': // User has confirmed that he is ready to create general db stuff.
		echo 'Installation step ' . $_POST['installstep'];
		require_once 'functions.php';
		require_once 'config/config.php'; 
		if ($db->connect_errno) {
			echo '<p style="color:red;">We can\'t connect to the database. Check your settings in config.php, or make sure the database exists at all.
			<p>Check these values in config.php:<br>  
			HOST_NAME: ' . HOST_NAME . '<br>
			DATABASE: ' . DATABASE . '<br>
			USER_NAME: ' . USER_NAME . '<br>
			PASSWORD: ' . PASSWORD . '<br>';
			exit();
		}
		// debug($db);
		else echo '<p style="color:green;">Connected to database ' . DATABASE . ' successfully.</p>';
		
		if ( $sectionconfig['users']['columns'] !== array ('name', 'password', 'email', 'role', 'status' )) {
			echo '<p style="color:red;">Make sure in config.php, $sectionconfig contains section \'users\' with these columns:</p>
			<pre>\'users\'=> array(\'columns\' => array (\'name\', \'password\', \'email\', \'role\', \'status\' ))</pre>';
			exit();
		}
		
		echo '<p>Now let\'s create a few basic things in the database.</p>';
		
		// create users table
		createTable('users'); 
	
		// create mapping table
		$sql = "CREATE TABLE maps ( 
			id INT(6) AUTO_INCREMENT PRIMARY KEY,
			mapling_section VARCHAR(30),
			mapling_id SMALLINT(6),
			mapped_section VARCHAR(30),
			mapped_id SMALLINT(6),
			rank TINYINT(4), 
			updated TIMESTAMP
		)";
		if ( $db->query($sql) !== true ) {
			echo '<p style="color:red;">Tried to create mapping table, but something went wrong. MySQL query: ' . $sql . '. Error: ' . $db->error .'"</p>';
		}
		else echo '<p style="color:green;">Created mapping table; MySQL query: ' . $sql . '"</p>';
	
		// create ranks table
		$sql = "CREATE TABLE ranks ( 
			id INT(6) AUTO_INCREMENT PRIMARY KEY,
			name VARCHAR(80),
			section VARCHAR(30),
			section_id SMALLINT(6)
		)";
		if ( $db->query($sql) !== true ) {
			echo '<p style="color:red;">Tried to create ranks table, but something went wrong. MySQL query: ' . $sql . '. Error: ' . $db->error .'"</p>';
		}
		else echo '<p style="color:green;">Created ranks table; MySQL query: ' . $sql . '"</p>';
		
		require_once 'functions.php';
			
		echo '<p>The next step will be to create all custom tables will all fields you have defined in config.php.<br>
			You may want to check them again?';
			
		debug($sectionconfig);
		
		echo '<form action="install.php" method="post">
			<input type="hidden" name="installstep" value="3">
			<input type="submit" value="Ready, let\'s go">';
		
		break; 
		
	case '3' : 
		echo 'Installation step ' . $_POST['installstep'];
		require_once 'functions.php';
		require_once 'config/config.php'; 
		// create tables for each section defined in config.php
		foreach ($sections as $onesection) { checkTable ($onesection); }
		
		echo'<p>Database looks good now. Make yourself the first superadmin and take it from there!</p>
		<form action="install.php" method="post">
			<input type="hidden" name="installstep" value="4">
			<input type="text" name="name" placeholder="username"/>
			<input type="text" name="email" placeholder="email"/>
			<input type="password" name="password" placeholder="password"/>
			<input type="password" name="rpassword" placeholder="repeat password"/>
			<input type="submit" value="Let\'s go">
		</form>';
	
		break; 
	
	
	case '4': 
		require_once 'functions.php'; 
		require_once 'config/config.php';
		if(!empty($_POST['name']) && !empty($_POST['password']) && !empty($_POST['rpassword']) && !empty($_POST['email'])) { // User has submittes registration data
		$name = mysqli_real_escape_string($db, $_POST['name']);
		$password = md5(mysqli_real_escape_string($db, $_POST['password']));
		$rpassword = md5(mysqli_real_escape_string($db, $_POST['rpassword']));
		$email = mysqli_real_escape_string($db, $_POST['email']);
		
		if ( $password == $rpassword ) { // Registration data correct
			$sql = "INSERT INTO users (name, password, email, role, status) VALUES('$name', '$password', '$email', 'superadmin', 'confirmed')";
			if ($result = $db->query($sql)) {
				echo '<h1>Success</h1><p>Your account was created. You can now proceed to the <a href="/system/index.php"> admin system and take it from there.<a/></p>';
				exit();
			}
			else echo '<h1>Yikes</h1><p>Sorry, something went wrong.</p>';   
		}
		else echo '<p><span class="uh">Passwords did not match. Please try again.</span></p>';
		echo '<form action="install.php" method="post">
				<input type="text" name="name" placeholder="username"/>
				<input type="text" name="email" placeholder="email"/>
				<input type="password" name="password" placeholder="password"/>
				<input type="password" name="rpassword" placeholder="repeat password"/>
				<input type="submit" value="Let\'s go">
			</form>';
		}
		else { 
			echo '<form action="install.php" method="post">
				<input type="text" name="name" placeholder="username"/>
				<input type="text" name="email" placeholder="email"/>
				<input type="password" name="password" placeholder="password"/>
				<input type="password" name="rpassword" placeholder="repeat password"/>
				<input type="submit" value="Let\'s go">
			</form>';
		}
}

?>

