<?php 
if ( $section != 'login' && $_SESSION['login']['status'] !== 'loggedin') header ("Location: /system/index.php");
echo '<!-- ' . basename(__FILE__) . '-->' . PHP_EOL ;

// include respectively autoload config, functions and classes
require_once ($_SERVER['DOCUMENT_ROOT'] . '/system/autoload.php');

date_default_timezone_set('Europe/Berlin'); 

// prepare data
$sectiondata = new sectiondata($section);
$firstitem = getFirstitem(); 
if ($section && in_array($section, array_keys($sectionconfig))) {				// this is a regular section, i.e. not main, graph....
	new tableCheck ($section); 													// check for missing tables and/or columns, create them automatically
}
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1 target-densitydpi=device-dpi" />
    <title><?php echo $title . ' | ' . $section; ?></title>
    <link href="//fonts.googleapis.com/css?family=Overpass:300,300i,400,700,800&amp;subset=latin-ext" rel="stylesheet">
    <link rel="stylesheet" href="/system/res/css/style.css"  type="text/css" />
    <link rel="stylesheet" href="/system/res/css/datepicker.css"  type="text/css" />
    <link rel="stylesheet" href="/config/config.css"  type="text/css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="/system/res/js/lazy.js"> // lazy load images</script>
    <script src='/system/res/js/datepicker.js'></script>
    <script src="/system/res/js/sorttable.js">// makes html tables sortable</script>
    <script src="/system/res/js/functions.js"></script>
    <script src="/system/res/js/onload.js"></script>
    <script>if (typeof jQuery != 'undefined') console.log('jQuery version: ' + jQuery.fn.jquery)// log jQuery version </script>

</head>

<body>
    
    <script> 
	// output stuff to js
    var section = "<?php echo $section; ?>"; 										// the current section
	var page = "<?php echo $page ?>"; 	// e.g. sectionview, itemview
	var teammate = "<?php echo $_SESSION['login']['teammate'] ?>";					// the teammate this user is mapped to
	var sections = <?php echo json_encode($_SESSION['login']['perm_sections']); ?>; // the sections permitted for this user
	var uploaddir = "<?php echo $uploaddir; ?>"; 									// path to image folder
	var sectiondata = <?php echo json_encode($sectiondata, JSON_FORCE_OBJECT) ?>; 	// section-specific columns, actions, tags, mappable, maplings, dialogs 
	// var filters = <? //php echo json_encode($_SESSION['filters'][$section], JSON_FORCE_OBJECT) ?>; 	// active filters for this section
	var filters = [];																// filters will be added to array upon filter selection
	var typedefs = <?php echo json_encode($type) ?>; 								// definitions of data columns; for editing values with correct dropdowns
	var dialog_actions = <?php echo json_encode($dialog_actions) ?>; 				// actions that require a modal dialogue; defined in config.php
	var dynamic_dialogs = <?php echo json_encode($dynamic_dialogs) ?>; 				// dialogues that require dynamic content; defined in config.php
    var message = <?php echo json_encode (											// output session message into js
		implode( flattenArray($_SESSION['message']), '<br>' 
	)); ?>; 
	
	// checks whether a message is set and displays message box
	showMsg(); 
	
	// keep session alive while page is open
	var refreshSn = function ()	{
		var time = 600000; // 10 mins
		settimeout(function (){
			$.ajax({
			   url: '/system/controller/keepSession.php',
			   cache: false,
			   complete: function () {refreshSn();}
			});
		},
		time);
	};
    </script>
    
	<?php 
	new viewHeader(); 																// Title, navigation links and login info
	// debug($sectiondata); 
	// debug($_SESSION);
	
	?>
        
    <div id="msgbox" class="info"><!-- content added with js --></div>
    
	<script> 
	showMsg(); 																		// checks whether a message is set and displays message box 
    </script>
    
    <?php 
	foreach ($sectiondata->dialogs as $dialog) {new viewDialog($dialog, $section);} // prepare modal dialogues as required
    echo '<!-- end ' . basename(__FILE__) . '-->' . PHP_EOL ; 
	?>
    
   
  