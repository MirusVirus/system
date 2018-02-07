<?php 

// delete any item and corresponding links and files

class delete {
	function __construct($section, $id) {
		global $db, $uploaddir;
		
		$this->section = $section; 
		$this->id = $id; 
		
		// if photo, get corresponding file names and delete them from directory
		if ($section == 'photos') {
			// delete file
			$this->file = sql('photos', 'file', $id);
			if (unlink($_SERVER['DOCUMENT_ROOT'] . $uploaddir . $this->file)) {
				$this->message[] = 'Deleted ' . $uploaddir . $this->file;
			}
			else $this->message[] = '<span class="uh">File ' . $uploaddir . $this->file . ' not found for deletion.</span>';
			// delete preview
			$this->preview = sql('photos', 'preview', $id);
			if (unlink($_SERVER['DOCUMENT_ROOT'] . $uploaddir . $this->preview)) {
				$this->message[] = 'Deleted ' . $uploaddir . $this->preview; 
			}
			else $this->message[] = '<span class="uh">File ' . $uploaddir . $this->preview . ' not found for deletion.</span>';
		}
		
		// delete the entry itself
		$sql = "DELETE FROM `$section` WHERE id = $id";
		if ($db->query($sql)) $this->message[] = 'Deleted ' . type($this->section) . ' ' . $this->id; 
		else $this->message[] = 'Problems deleting ' . type($this->section) . ' ' . $this->id; 
			
		// delete related mappings
		$sql = "DELETE FROM maps WHERE mapped_section = '$this->section' AND mapped_id = $this->id";
		$db->query($sql);
		$this->mappedcount = mysqli_affected_rows($db);
		if ( $this->mappedcount > 0 ) $this->message[] = 'Deleted '.$this->mappedcount.' link(s) to '.type($this->section).' '.$this->id;
			
		$sql = "DELETE FROM maps WHERE mapling_section = '$section' AND mapling_id = $id";
		$db->query($sql);
		$this->maplingcount = mysqli_affected_rows($db);
		if ( $this->maplingcount > 0 ) $this->message[] = 'Deleted '. $this->maplingcount.' link(s) from '.type($this->section).' '.$this->id;

	}
}
?>