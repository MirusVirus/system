<?php
class image {
	var $path; 			// the full path to image on server, including (temp) file name
	var $filename; 		// the original name of the uploaded file, e.g. famousdesign.jpg
	var $extension; 	// file extension - use this for now to recognize svg
	var $name; 			// 
	var $descr; 		// Caption or description
	var $tags; 			//
	var $type; 			// e.g. image/svg+xml, image/jpeg, image/png
	var $width; 
	var $height;
	var $depth; 		// color depth
	var $channels; 		// number of color channels
	var $memory; 		// estimated memory required to edit the image
	var $tmpimage; 		// the image resource to create from uploaded file
	var $newFullWidth; 	// width of file to be created for full photo view
	var $newFullHeight; // height of file to be created for full photo view
	var $newPreviewWidth; // height of file to be created for photo preview
	var $newPreviewHeight; // height of file to be created for photo preview
	var $fullview; 		// the name of the full image file as recorded in db, without path
	var $preview; 		// the name of the preview file as recorded in db, without path
	var $status; 		// if not 'OK', steps will be skipped 
	var $message; 		// holds messages from image handling, e.g. for AJAX response
	
	function __construct($path, $filename, $name = null, $descr = null, $tags = null) {
		$this->path = $path;
		$this->filename = $filename;
		$this->extension = pathinfo( $this->filename, PATHINFO_EXTENSION );
		$this->name = $name ? $name : pathinfo( $filename, PATHINFO_FILENAME );
		$this->descr = $descr ? $descr : $filename; 
		$this->tags = $tags;
		$info = getimagesize($path);		
		$this->type = $info['mime'];
		$this->width = $info[0];
		$this->height = $info[1];
		$this->depth = $info['bits'];
		$this->channels = $info['channels'];
		$this->memory = round((($this->width * $this->height * $this->depth * $this->channels / 8 + Pow(2, 16)) * 1.65)/1000) . 'K';
		$this->status = 'OK';
		$this->message[] = 'Input file: ' . $this->filename;
	}
	
	function getNewsize() {
		$ratio = $this->width / $this->height; 								// this ratio must stay the same
	// full photo
		global $maxWidth; 
		global $maxHeight;
		$maxRatio = $maxWidth / $maxHeight;
		if ( $this->width <= $maxWidth && $this->height <= $maxHeight ) { 	// no resizing needed for full photo
			$this->newFullWidth = $this->width; 
			$this->newFullHeight = $this->height;
		}
		else { // resizing needed for full photo
			if ($maxRatio > $ratio) { // Height determines scale factor
				$this->newFullHeight = $maxHeight; 
				$this->newFullWidth = $this->newFullHeight * $ratio; 
			}
			else { // Width determines scale factor, height for preview, or ratio equals maxRatio.
				$this->newFullWidth = $maxWidth;
				$this->newFullHeight = $this->newFullWidth / $ratio;
			}
		}
	// preview
		global $minWidth; 
		global $minHeight;
		$minRatio = $minWidth / $minHeight;
		if ($minRatio > $ratio) { // Width determines scale factor.
			$this->newPreviewWidth = $minWidth; 
			$this->newPreviewHeight = $this->newPreviewWidth / $ratio; 
		}
		else { // Height determines scale factor, or ratio equals maxRatio.
			$this->newPreviewHeight = $minHeight;
			$this->newPreviewWidth = $this->newPreviewHeight * $ratio;
		}
	}
	
	function db() { // create db entry for new photos
		global $db;
		global $suffix;
		$sql = "INSERT INTO photos ( name, descr, tags ) VALUES ( '$this->name', '$this->descr', '$this->tags' );";
		if(!$result = $db->query($sql)){
			$this->status = 'Error';
			$this->message[] = '<span class="uh">Error running the query [' . $sql . ']</span>';
		} 
		$this->id = $db->insert_id; // gets the id of this db insert
		if ( $this->type == 'image/jpeg' || $this->type == 'image/png' || $this->type == 'image/gif'  ) {
			$this->fullview = 'photo' . $this->id . '.jpg';
			$this->preview = 'photo' . $this->id . $suffix . '.jpg';
		}
		elseif ( $this->extension == 'svg' ) {
			$this->fullview = 'photo' . $this->id . '.svg';
			$this->preview = 'photo' . $this->id . $suffix . '.svg';
		}
		else { 
			$this->status = 'Error'; $this->message[] = '<span class="uh">Unknown file format:'  . $this->filename . '</span>';
		}
		$sql = "UPDATE photos SET file = '$this->fullview', preview = '$this->preview' WHERE id = '$this->id';"; // now assign a file name containing that id
		if(!$result = $db->query($sql)){ 
			$this->status = 'Error'; $this->message[] = '<span class="uh">Error running the query [' . $sql . ']</span>'; //Fehlermeldung
		} 
		else { $this->message[] = 'Saved image info: <br>' . $this->fullview . ', ' . $this->preview; }
	}
	
	function tmpImage() { // create temporary image resource
		if ( $this->type == 'image/jpeg') {
			if ( $this->tmpimage = imagecreatefromjpeg( $this->path )) {}
			else { $this->status = 'Error'; $this->message[] = '<span class="uh">Problem creating temporary image from jpg.</span>' ; }
		}
		elseif ( $this->type == 'image/png') {
			if ( $this->tmpimage = imagecreatefrompng( $this->path )) {}
			else { $this->status = 'Error'; $this->message[] = '<span class="uh">Problem creating temporary image from png.</span>' ; }
		}
		elseif ( $this->type == 'image/gif') {
			if ( $this->tmpimage = imagecreatefromgif( $this->path )) {}
			else { $this->status = 'Error'; $this->message[] = '<span class="uh">Problem creating temporary image from gif.</span>' ; }
		}
		else { 
			$this->status = 'Error'; $this->message[] = '<span class="uh">Unknown file type - can\'t create temp image.</span>';
		}
	}
	
	function newImage($width, $height, $quality, $filename) { // for pixel files
		global $uploaddir;
		if ( $newImage = imagecreatetruecolor( $width, $height )) { 
			if ( imagecopyresampled( $newImage, $this->tmpimage, 0, 0, 0, 0, $width, $height, $this->width, $this->height )) {
				if ( imagejpeg( $newImage, $_SERVER['DOCUMENT_ROOT'] . $uploaddir . $filename, $quality )) {
					$this->message[] = 'File saved: ' . $uploaddir . $filename;
				}
				else { $this->status = 'Error'; $this->message[] = '<span class="uh">Problems saving file: '.$uploaddir.$filename.'</span>'; }
			}
			else { $this->status = 'Error'; $this->message[] = '<span class="uh">Problems resampling image.</span>'; }
		}
		else { $this->status = 'Error'; $this->message[] = '<span class="uh">Problems creating blank image.</span>';	}
	}
	
	function deleteInput() {
		$this->message[] = ( unlink( $this->path)) ? 'Deleted file ' . $this->path : 'Problems removing file ' . $this->path; 
	}
		
	
	function copyImage() { // for svg files
		global $uploaddir;
		if ( copy( $this->path, $_SERVER['DOCUMENT_ROOT'] . $uploaddir . $this->fullview )) {
			$this->message[] = 'Copied file to ' . $uploaddir . $this->fullview; 
		}
		else { 
			$this->status = 'Error'; 
			$this->message[] = '<span class="uh">Unable to copy '.$this->path.' to '.$_SERVER['DOCUMENT_ROOT'].$uploaddir.$this->fullview.'</span>';
		}
		if ( copy( $this->path, $_SERVER['DOCUMENT_ROOT'] . $uploaddir . $this->preview )) {
			$this->message[] = 'Copied file to ' . $uploaddir . $this->preview; 
		}
		else { 
			$this->status = 'Error'; 
			$this->message[] = '<span class="uh">Unable to copy file ' . $this->path . ' to ' . $_SERVER['DOCUMENT_ROOT']. $uploaddir . $this->preview . '</span>';}
	}
	
	function process() { // the final function patching the other functions together
		if ( $this->extension == 'svg' ) {// svg vectors
			$this->db(); 
			if ($this->status == 'OK') $this->copyImage();
		}
		else { // pixel images
			global $maxUploadwidth, $maxUploadheight, $msgbreak, $pixels;
			if ( $this->width > $maxUploadwidth || $this->height > $maxUploadheight ) { // file too large
				$this->message[] = '<span class="uh">Image ' . $this->filename . ' is too large and was skipped. <br>
				Allowed width: ' . $maxUploadwidth . '; actual width: ' . $this->width . '<br>allowed height: ' . $maxUploadheight . '; actual height: ' . $this->height . '</span>';
			}
			else { // file OK
				$this->db(); 
				global $photoQuality, $previewQuality;
				if ($this->status == 'OK') $this->tmpImage();
				if ($this->status == 'OK') $this->getNewsize(); 
				if ($this->status == 'OK') $this->newImage($this->newFullWidth, $this->newFullHeight, $photoQuality, $this->fullview );
				if ($this->status == 'OK') $this->newImage($this->newPreviewWidth, $this->newPreviewHeight, $previewQuality, $this->preview );
				imagedestroy($this->tmpimage);
			}
		}
		if ($this->status == 'OK') $this->deleteInput();
		$this->message[] = ' '; // insert an empty line to create one paragraph in message box per image
		
	}	
}
?>