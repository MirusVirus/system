<?php 
require_once ($_SERVER['DOCUMENT_ROOT'] . '/system/ini.php');		// basic configuration - same for all projects
require_once ($_SERVER['DOCUMENT_ROOT'] . '/system/functions.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/system/helpers.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/config/config.php'); // customized configuration - project-specific

spl_autoload_register(function ($class) {
	
	$folders = array( // class files in these folders will be autoloaded
		$_SERVER['DOCUMENT_ROOT'] . '/system/model/',
		$_SERVER['DOCUMENT_ROOT'] . '/system/views/',
		$_SERVER['DOCUMENT_ROOT'] . '/config/prints/',
		$_SERVER['DOCUMENT_ROOT'] . '/config/customviews/',
		$_SERVER['DOCUMENT_ROOT'] . '/system/controller/'); 
		
    foreach ($folders as $folder) {
        if (file_exists($folder.$class.'.php')) {
            require_once $folder.$class.'.php';
        } 
    } 
});
?>
