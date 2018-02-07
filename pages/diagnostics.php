<?php 
session_start();
$section = 'diagnostics';
$_SESSION['message'][] = 'Completed full diagnostics.';
require_once ($_SERVER['DOCUMENT_ROOT'] . '/system/documenthead.php');  // includes <body> tag as well as jquery, sorttable.js, datepicker.js
?>

<span class="comment">The current server timezone is: <?php echo date_default_timezone_get() ?></span><br>
 

<fieldset class="overview"><legend><h3>Files without DB entries</h3></legend>
    <?php 
    $photos = new listItems('photos'); 
    $files_inDB = array(); 
    foreach ($photos->items as $photo) {
        $files_inDB[] = $photo->columns['file']; 
        $files_inDB[] = $photo->preview; 
    }
    
    $files = array_diff( scandir($_SERVER['DOCUMENT_ROOT'] . $uploaddir), array('.', '..'));
    
    $garbagefiles = array_diff($files, $files_inDB);
    
    debug($garbagefiles); 
    ?>
</fieldset>


</body>
</html>