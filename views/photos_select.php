<!-- start photos.select.php -->

<?php 
// $mapsuggest_section = $_GET['mapsuggest_section']; // for "Save and link to ..." option
// $mapsuggest_id = $_GET['mapsuggest_id']; // for "Save and link to ..." option
$uploadfiles = array_diff(scandir($_SERVER['DOCUMENT_ROOT'] . $newuploaddir), array('.', '..'));
?>

<fieldset>
   	<legend><h3>Upload new images</h3></legend>
      <form class="input" enctype="multipart/form-data" action="/system/controller/photos_upload.php" method="POST">
       <input type="hidden" name="MAX_FILE_SIZE" value="20000000" />
       <input type="hidden" name="referring" value="<?php echo $section;?>" /> <!-- to know where we came from -->
        <table><tbody>
        <tr>
        <td>Photo 1:</td>
        <td><input name="photo1" type="file" accept="image/*" /></td>
        <td><input name="url1" type="text" placeholder="alternatively: URL" /></td>
        <td><input name="name1" type="text" placeholder="Name"/></td>
        <td><textarea name="descr1" 	rows="2" cols="70" placeholder="Description" ></textarea>
        </tr>
        <tr>
        <td>Photo 2:</td>
        <td><input name="photo2" type="file" accept="image/*" /></td>
        <td><input name="url2" type="text" placeholder="alternatively: URL" /></td>
        <td><input name="name2" type="text" placeholder="Name"/></td>
        <td><textarea name="descr2" 	rows="2" cols="70" placeholder="Description" ></textarea>
	    </tr>
        <tr>
        <td>Photo 3:</td>
        <td><input name="photo3" type="file" accept="image/*" /></td>
        <td><input name="url3" type="text" placeholder="alternatively: URL" /></td>
        <td><input name="name3" type="text" placeholder="Name"/></td>
        <td><textarea name="descr3" 	rows="2" cols="70" placeholder="Description" ></textarea>
        </tr>
        </tbody></table>
       <input type="submit" value="Upload photos(s)" />&nbsp; &nbsp;
       <?php
	  
		if ( !empty ($mapsuggest_section) && !empty ($mapsuggest_id)) { 
			echo ' <input type="submit" name="saveandlink" value="Upload and link to '. type($mapsuggest_section) . ' ' . $mapsuggest_id . ': ' . sql($mapsuggest_section, 'name', $mapsuggest_id) . '">';
		}
		?>
   	</form><br>
</fieldset>
<fieldset>
   	<legend><h3>FTP upload: <?php echo count ($uploadfiles) ?> files waiting to be processed</h3></legend>
    <span class="comment">Alternatively to the upload with the above form, you can upload image files via FTP to this directory:<strong> <?php echo $newuploaddir; ?>.</strong> Files may be jpg, png, gif or svg.
	<strong>Max. <?php echo $maxUploadwidth; ?> x <?php echo $maxUploadheight; ?> pixels please. No CMYK.</strong> <br>
    All files in directory will be processed and moved to<?php echo $uploaddir; ?>. File names will become photo headlines.</span><br>
	<?php if( count($uploadfiles) > 0 && count($uploadfiles) <= $batch ) : ?>
		<button onclick="ajax({action:'processNewuploads', alertmsg:'Wait for confirmation message, then reload page to see new files.'});">Process files now</button><br>
    <?php elseif ( count($uploadfiles) > $batch ) : ?> 
    	<button onclick="ajax({action:'processNewuploads', alertmsg:'Wait for confirmation message, then reload page to see new files.'});">Process first <?php echo $batch; ?> files now</button><br>
    <?php elseif ( count($uploadfiles) < 1 ) : ?> 
		<button onclick="alert('You need to upload files before you can process them.');">Process files now</button><br>
	<?php endif; 
	echo implode($uploadfiles, ', ');
	?>
</fieldset>

<!-- end photos.select.php -->
<?php echo PHP_EOL; ?>