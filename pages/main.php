<?php 
session_start();
$section = 'main';
require $_SERVER['DOCUMENT_ROOT'] . '/system/documenthead.php';  // includes <body> tag as well as jquery, sorttable.js, datepicker.js

// debug($_SESSION); 

// prepare data
$overviewdata = new overviewdata();									// as the name says

// show custom views, first position
if ($_SESSION['customviews'][$section]['first']) new $_SESSION['customviews'][$section]['first']();
// debug($_SESSION['customviews']);

// display all sections
new viewOverview($overviewdata); 

// non section-related stuff
echo '<fieldset class="overview">';
echo (new viewButton('search'))->html;
echo '<legend><h2>Tools &amp; diagnostics</h2></legend>';
echo '<a href="/system/phpinfo.php" target="_blank"><button>PHP info</button></a>';
echo '<a href="/system/pages/diagnostics.php" target="_blank"><button>Diagnostics</button></a>';
echo '<button onclick="ajax({action: \'checkAJAX\'});">AJAX check</button>';
echo '</fieldset>';

// show custom views, last position
if ($_SESSION['customviews'][$section]['last']) new $_SESSION['customviews'][$section]['last']();

?>
 
</body>
</html> 