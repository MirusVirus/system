<?php 

// provides an upload dialogue for files 
$uploadfiles = array_diff(scandir($_SERVER['DOCUMENT_ROOT'] . $newuploaddir), array('.', '..'));

class viewFileupload {
	function __construct() {
				
		// the html
		$this->html = '<div id="uploadzone">'; 
		$this->html.= '<span style="margin-right:10px; color:white;">Drop files here</span>';
		$this->html.= '<img src="/system/img/dragndrop.svg" class="dragndrop_icon">';

		$this->html.= '</div>'; 
		
		// the js
		$this->script = '<script src=\'/system/res/js/upload.js\'></script>'; 
	}
}
?>