<?php

/********* Basic configuration, same for all projects ***********/

// Database access. Live and test server use same database.
		
/* List of sections and definitions for dynamic database queries. 
	'columns' = full set of columns, except `id` and `updated`(added automatically for all tables)
	'short' = selected set of columns, used for lean display. Usually `name` except for photos -> `file` 
	'actions' = specific actions in addition to $general_actions
	'map' = sections that this section can be mapped to
	'filter' = 
	'analysis' = 
	'sorting' = default sorting order for display
*/

$sectionconfig =array ( 		// only stuff that's needed for all projects. Custom sections will be appended in config/config.php
	
	'photos'  => array( 
		'columns' => array ('file', 'name', 'descr', 'tags' ),
		'short'   => array ('preview'),
		'actions' => array (),
		
	),
	
	'users'	     => array( 
		'columns' => array ('name', 'password', 'email', 'role', 'status', 'lang', 'last_login', 'login_count', 'token' ),
		'short'   => array ('name'), 
		'actions' => array ('confirm'),
		'map'	  => array ('teammates'),
		'sorting' => array ('name ASC' ),
		'perm'    => array ('superadmin')
	),
); 

$general_columns = array('id', 'created', 'updated'); // must-have columns. If they are to be displayed, list them again in $sectionconfig!
$general_actions = array('viewDetails', 'update', 'delete', 'copy', 'duplicate'); // actions that are possible in all sections
$dialog_actions  = array('confirm', 'copy', 'delete', 'duplicate', 'map1', 'map2', 'search', 'urlphoto'); // actions that require a modal dialogue called with js
$dynamic_dialogs = array('copy', 'map1', 'map2', 'search'); // dialogue that require dynamic content added with js

/* Definitions: for creating form_input.php, for display, for creating db tables */

$type = array (
// general
	'id'			=> array ( 'type' => 'id', 'sql' => 'SMALLINT(6) AUTO_INCREMENT PRIMARY KEY', 'label' => 'ID'),
	'created' 		=> array ( 'type' => 'datetime', 'sql' => 'TIMESTAMP NOT NULL DEFAULT 0', 'label' => 'Created', 'editable' => 'false' ),
	'updated' 		=> array ( 'type' => 'datetime', 'sql' => 'TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW()',	'label' => 'Updated', 'editable' => 'false' ), 
	'descr' 		=> array ( 'type' => 'textarea','sql' => 'text',		'label' => 'Description' ),
	'name'  		=> array ( 'type' => 'text', 	'sql' => 'varchar(120)','label' => 'Name' ),
	'text_short' 	=> array ( 'type' => 'textarea','sql' => 'text',		'label' => 'Text, short version' ),
	'text_long' 	=> array ( 'type' => 'textarea','sql' => 'text',		'label' => 'Text, long version' ),
	'tags' 			=> array ( 'type' => 'tags', 	'sql' => 'varchar(80)',	'label' => 'Tags' ),
	'rank' 			=> array ( 'type' => 'dropdown','sql' => 'tinyint(4)',	'label' => 'Rank' ),
	'file' 			=> array ( 'type' => 'file', 	'sql' => 'varchar(80)',	'label' => 'File' ),

// user mgmt
	'email' 		=> array ( 'type' => 'text', 	'sql' => 'varchar(80)',	'label' => 'E-mail' ),
	'lang' 			=> array ( 'type' => 'dropdown', 'sql' => 'varchar(10)', 'label' => 'Language', 
							'dropdown' => array ( 'en', 'pl', 'de' )), 
	'password' 		=> array ( 'type' => 'text',     'sql' => 'varchar(80)', 'label' => 'Password', 'editable' => 'false' ), 
	'role' 			=> array ( 'type' => 'dropdown', 'sql' => 'varchar(80)', 'label' => 'Role', 
							'dropdown' => array( 'superadmin')), 
	'token'			=> array ( 'type' => 'text', 	 'sql' => 'varchar(80)', 'label' => 'Token for reset', 'editable' => 'false' ),
	'last_login' 	=> array ( 'type' => 'datetime', 'sql' => 'varchar(80) NOT NULL', 'label' => 'Last login', 'editable' => 'false'), 
	'login_count'	=> array ( 'type' => 'text',	 'sql' => 'smallint(6) DEFAULT 0', 'label' => 'Login count', 'editable' => 'false'),
	'status'		=> array ( 'type' => 'dropdown','sql' => 'varchar(80) DEFAULT \'waiting\'',	'label' => 'Status', 
						'dropdown' => array( 'waiting', 'confirmed' ))
);

/* CI */
$title = 'Intranet';
$comp_name = 'Company name'; 
$logo1 = '/config/img/KA_neg_rgb.svg';
$systemurl = 'kranich-automobile.de/system';

/* Photos */

$suffix = '_preview'; 						// suffix for preview images

$memory = 140000; 							// Max available memory in Kbytes. Photos expected to exceed this will not be processed
$maxUploadwidth = 6000; 					// Max allowed image width for uploads
$maxUploadheight = 6000; 					// Max allowed image height for uploads
$maxPixels = 6000000; 
$batch = 10; 								// images processed in a row

/* Preparing some variables from the above. Don't edit here. */

$sections = array_keys ( $sectionconfig ); // need this for many occasions

/* texts */
$texts = array(
	'Login' => array(
		'en' => 'Login', 
		'pl' => 'Zaloguj się',
	 	'de' => 'Login'
	),
	'username' => array(
		'en' => 'User name', 
		'pl' => 'Nazwa użytkownika',
	 	'de' => 'Benutzername'
	),
	'password' => array(
		'en' => 'Password', 
		'pl' => 'Hasło',
	 	'de' => 'Passwort'
	),
	'rpassword' => array(
		'en' => 'Repeat password', 
		'pl' => 'Powtórz hasło',
	 	'de' => 'Passwort wiederholen'
	),
	'Logout' => array(
		'en' => 'Logout', 
		'pl' => 'Wyloguj się',
	 	'de' => 'Logout'
	),
	'Reset' => array(
		'en' => 'Reset', 
		'pl' => 'Zresetuj',
	 	'de' => 'Reset'
	),
	'login_welcome' => array(
		'en' => 'Aloha! Please either login, or <a href="register.php">click here to register</a>.', 
		'pl' => 'Aloha! Zaloguj się, lub kliknij <a href="register.php">tutaj, aby się zarejestrować</a>.',
	 	'de' => 'Aloha! Log Dich ein, oder klick <a href="register.php">hier, um Dich zu registrieren</a>.'
	),
	'login_forgotPW' => array(
		'en' => 'Forgot your password? <a href="resetPW.php">Click here to choose a new one.</a>.', 
		'pl' => 'Nie pamiętasz hasła? <a href="resetPW.php">Tutaj możesz wybrać nowy</a>.',
	 	'de' => 'Passwort vergessen? <a href="resetPW.php">Hier kannst Du ein neues wählen</a>.'
	), 
	'logout_success' => array(
		'en' => 'Logout successful!', 
		'pl' => 'Jesteś wylogowany!',
	 	'de' => 'Logout erfolgreich!'
	),
	'logout_redirect' => array(
		'en' => 'We are now forwarding you to the login page.', 
		'pl' => 'Przekazujemy Cię na stronę logowania.',
	 	'de' => 'Du wirst zur Login-Seite weitergeleitet.'
	), 
	'Register' => array(
		'en' => 'Create account', 
		'pl' => 'Załóż konto',
	 	'de' => 'Konto anlegen'
	),
	'register_welcome' => array(
		'en' => 'Please enter your details below to create an account.', 
		'pl' => 'Podaj twoje dane poniżej, aby założyć konto.',
	 	'de' => 'Gib hier Deine Daten ein, um ein Konto anzulegen.'
	),
	'Reset password' => array(
		'en' => 'Reset password', 
		'pl' => 'Zresetuj hasło',
	 	'de' => 'Passwort zurücksetzen'
	),
	'resetPW_welcome' => array(
		'en' => 'Please enter your data below, so we can send you a link to reset your password:', 
		'pl' => 'Wpisz twoje dane, abyśmy mogli wysłać Ci link do zresetowania hasła:',
	 	'de' => 'Gib Deine Daten ein, damit wir Dir einen Link zum Passwort-Reset schicken können:'
	),
	'resetPW_instructions' => array(
		'en' => ', we found you in our records. Check your e-mail and click the link to reset your password.', 
		'pl' => ', znaleźliśmy twój konto. Sprawdź e-mail i kliknij link, aby zresetować hasło.',
	 	'de' => ', wir haben Dein Konto gefunden. Check Deine e-Mails und klick den Link, um Dein Passwort zurückzusetzen.'
	),
	'resetPW_unknown' => array(
		'en' => 'Sorry, we didn\'t find your account.<br>Contact our administrator (' . EMAIL_ADDRESS . '), or <a href="register.php">register</a> again.', 
		'pl' => 'Przepraszamy, nie znaleźliśmy twojego konta.<br>Skontaktuj się z naszym administratorem (' . EMAIL_ADDRESS . ') lub <a href="register.php">załóż nowe konto</a>.',
	 	'de' => 'Leider haben wir Dein Konto nicht gefunden.<br>Wende Dich an den Admin (' . EMAIL_ADDRESS . '), oder <a href="register.php">erstelle ein neues Konto</a>.'
	),
	'resetPW_mailbody' => array(
		'en' => 'you forgot your password, you said? Click this link to set a new one:', 
		'pl' => 'Zapomniałeś hasła, powiedziałeś? Kliknij ten link, aby ustawić nowy:',
	 	'de' => 'Du hast Dein Passwort vergessen? Klick diesen Link, um ein neues festzulegen:'
	),
	'Set password' => array(
		'en' => 'Set password', 
		'pl' => 'Ustaw hasło',
	 	'de' => 'Passwort festlegen'
	),
	'SetPW_welcome' => array(
		'en' => 'Choose a new password:', 
		'pl' => 'Wybierz nowe hasło:',
	 	'de' => 'Wähle ein neues Passwort:'
	),
	'PW_mismatch' => array(
		'en' => 'Passwords did not match. Please try again.', 
		'pl' => 'Hasła się nie zgadzają. Proszę spróbuj ponownie.',
	 	'de' => 'Die Passwörter stimmen nicht überein. Bitte noch mal versuchen.'
	),
	'Done' => array(
		'en' => 'Done.', 
		'pl' => 'Załatwione.',
	 	'de' => 'Erledigt.'
	),
	'redirect' => array(
		'en' => '<a href="/system/index.php">Click here</a> if redirect is not working within 5 seconds.', 
		'pl' => '<a href="/system/index.php">Kliknij tutaj</a> jeśli przekierowanie nie działa w ciągu 5 sekund.',
	 	'de' => '<a href="/system/index.php">Hier klicken</a>, falls Du nicht innerhalb von 5 Sekunden weitergeleitet wirst.'
	),
	'backtologin' => array(
		'en' => 'Back to <a href="/system/index.php">login</a>', 
		'pl' => 'Wróć do <a href="/system/index.php">logowania</a>',
	 	'de' => 'Zurück zum <a href="/system/index.php">Login</a>'
	), 
	// viewStatus 
	'notloggedin' => array(
		'en' => 'You are not logged in.', 
		'pl' => 'Nie jesteś zalogowany.',
	 	'de' => 'Du bist nicht eingeloggt.'
	), 
	'loggedinas' => array(
		'en' => 'Logged in as ', 
		'pl' => 'Zalogowano jako ',
	 	'de' => 'Eingeloggt als '
	), 
	'yourrole' => array(
		'en' => 'Your role: ', 
		'pl' => 'Twoja rola: ',
	 	'de' => 'Deine Rolle: '
	), 
	// buttons
	'confirm' => array(
		'en' => 'Confirm user', 
		'pl' => 'Potwierdź użytkownika',
	 	'de' => 'Nutzer bestätigen'
	), 
	'copy' => array(
		'en' => 'Copy to section ...', 
		'pl' => 'Skopiuj do sekcji ...',
	 	'de' => 'Kopieren in die Sektion ...'
	), 
	'delete' => array(
		'en' => 'Delete', 
		'pl' => 'Usuń',
	 	'de' => 'Entfernen'
	), 
	'details' => array(
		'en' => 'View details', 
		'pl' => 'Pokaż szczegóły',
	 	'de' => 'Detailansicht'
	), 
	'duplicate' => array(
		'en' => 'Duplicate', 
		'pl' => 'Duplikuj',
	 	'de' => 'Duplizieren'
	), 
	'firstitem' => array(
		'en' => 'Make no. 1', 
		'pl' => 'Niech to będzie numer jeden',
	 	'de' => 'Zur Nr. 1 machen'
	), 
	'graph' => array(
		'en' => 'Graph view', 
		'pl' => 'Widok wykresu',
	 	'de' => 'Graphansicht'
	), 
	'manageMaps' => array(
		'en' => 'Manage ', 
		'pl' => 'Zarządzaj ',
	 	'de' => 'Verwalte '
	), 
	'map1' => array(
		'en' => 'Link to ', 
		'pl' => 'Połącz z ',
	 	'de' => 'Verlinken mit '
	), 
	'map2' => array(
		'en' => 'Link ', 
		'pl' => 'Połącz ',
	 	'de' => 'Verlinke '
	), 
	'print' => array(
		'en' => 'Print', 
		'pl' => 'Wydrukuj',
	 	'de' => 'Drucken'
	), 
	'save' => array(
		'en' => 'Save', 
		'pl' => 'Zapisuj',
	 	'de' => 'Speichern'
	), 
	'search' => array(
		'en' => 'Search', 
		'pl' => 'Poszukaj',
	 	'de' => 'Suchen'
	), 
	'tag' => array(
		'en' => 'Your role: ', 
		'pl' => 'Twoja rola: ',
	 	'de' => 'Deine Rolle: '
	), 
	'unfilterAll' => array(
		'en' => 'Remove all filters', 
		'pl' => 'Usuń wszystkie filtry',
	 	'de' => 'Alle Filter entfernen'
	), 
	'update' => array(
		'en' => 'Update', 
		'pl' => 'Zaktualizuj',
	 	'de' => 'Bearbeiten'
	), 
	'urlphoto' => array(
		'en' => 'Link&nbsp;new&nbsp;photo&nbsp;from&nbsp;url', 
		'pl' => 'Połącz&nbsp;nowe&nbsp;zdjęcie&nbsp;z&nbsp;URL',
	 	'de' => 'Neues&nbsp;Photo&nbsp;von&nbsp;URL&nbsp;verlinken'
	), 
	'viewDetails' => array(
		'en' => 'View details', 
		'pl' => 'Pokaż szczegóły',
	 	'de' => 'Detailansicht'
	), 
	// table head labels
	'linked' => array(
		'en' => 'Linked ', 
		'pl' => 'Połączone ',
	 	'de' => 'Verlinkte '
	),
	'links' => array(
		'en' => 'Links to other things', 
		'pl' => 'Linki do innych rzeczy',
	 	'de' => 'Links zu anderen Sachen'
	),
	'options' => array(
		'en' => 'Options', 
		'pl' => 'Opcje',
	 	'de' => 'Optionen'
	)
);
		
$register_subject = 'New registration pending'; 
$register_mailbody = ' has registered and is waiting for your confirmation.';

$confirm_users_subject = 'Registration confirmed!'; 
$confirm_users_mailbody = '<p>Your registration for the ' . $title . ' system has been confirmed. 
	Congratulations!<br><a href="' . $systemurl . '">Log in here.</a></p>
	<p>Sincerely, your ' . $title . 'team</p>';

$confirm_accreditations_subject = 'Accreditation confirmed!'; 
$confirm_accreditations_mailbody = '<p>We are pleased to confirm your state of DESIGN press accreditation. 
	Looking forward to meeting you!</p>
	<p>Sincerely, <br>your state of DESIGN team</p>';

$resetPW_subject = $comp_name . ' - password reset';
$resetPW_mailbody = 'you forgot your password, you said? Click the link below to set a new one:'; 
	
/* Stuff */
$msgbreak = '<span class="msgbreak"></span>';

for ( $i=1; $i<100; $i++ ) $ranks[] = $i;
$type['rank']['dropdown'] = $ranks;

for ( $i=1; $i<54; $i++ ) $cal_weeks[] = $i;
$type['cal_week']['dropdown'] = $cal_weeks;

for ( $i=1; $i<13; $i++ ) $months[] = $i;
$type['month']['dropdown'] = $months;

$Months[] = 'Jan'; 
$Months[] = 'Feb';
$Months[] = 'Mar'; 
$Months[] = 'Apr'; 
$Months[] = 'May';
$Months[] = 'Jun'; 
$Months[] = 'Jul'; 
$Months[] = 'Aug';
$Months[] = 'Sep'; 
$Months[] = 'Oct'; 
$Months[] = 'Nov';
$Months[] = 'Dec'; 
// $type['month']['dropdown'] = $months;

?>