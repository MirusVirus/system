<?php 
// displays items in print queue; user can remove items or choose to print. Goes by section

session_start();
$section = 'print';
require $_SERVER['DOCUMENT_ROOT'] . '/system/documenthead.php';  			// includes <body> tag as well as jquery, sorttable.js, datepicker.js

// print data
require_once $_SERVER['DOCUMENT_ROOT'] . '/system/res/php/TCPDF-master/tcpdf.php';
$printoptions = new printoptions();
// debug($printoptions);

// Are there print options type->selectItemlist? 

echo '<fieldset>';
echo '<legend><h2>Items ready to be printed</h2></legend>';		

// Display print queue by section (because each section has its own print config)
if ( count(array_keys($_SESSION['print'])) > 0 ) {
	foreach (array_keys($_SESSION['print']) as $printSection) {
		echo (new viewPrint($printSection))->html;
	}
	echo '<br></br><a href="/system/controller/emptyPrintqueue.php"><button>Empty all print queues</button></a>';
}
else echo '<p class="comment">Nothing in print queue. Click "Print" button in columm "Options" to add items.</p>';
echo '</fieldset>'.PHP_EOL;


// Pre-made prints that don't require selecting items

echo '<fieldset>';
echo '<legend><h2>Pre-defined prints</h2></legend>';						// Headline with information

foreach ( $printoptions->print_classes as $print_class ) {
	if ( $print_class['printtype'] == 'global' ) {
		echo '<h3>' . $print_class['name'] . '</h3>';
		echo '<form class="input" method="post" action="/system/pages/outputPrint.php?output=' . $print_class['classname'] . '" target="_blank">';
		echo $print_class['controls'];
		echo '<input type="submit" value="Print ' . $print_class['name'] . '"></input></form>'.PHP_EOL;
	}
}

/*
// Timesheets

echo '<h3>Time sheets</h3>';
echo '<form class="input" method="post" action="/system/pages/outputPrint.php?output=printTimesheet" target="_blank">';
echo '<select name="param">'.PHP_EOL;

$currentweek = (new DateTime())->format("W");
for ($week = 1; $week <= 53; $week++) {										// Loop through calendar weeks
    echo '<option value="' . $week . '" ';
	if ($week == $currentweek ) echo 'selected="selected"';					// pre-select current week
	echo '>Cal. week: ' .  $week . '</option>'.PHP_EOL;
} 
echo '</select>'.PHP_EOL;
echo '<br><input type="submit" value="Print timesheet"></input></form>'.PHP_EOL;


echo '<h3>Accounts</h3>';
echo '<a href= "/system/pages/outputPrint.php?output=printAccounts" target="_blank">';
echo '<button>Print accounts</button></a>';
*/

echo '</fieldset>'.PHP_EOL;

?>





