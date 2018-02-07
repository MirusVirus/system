<!-- this is to manage the assignment of photos to events, projects etc. To edit photo captions, use photos_update.php. -->
<?php 
session_start();
require_once ($_SERVER['DOCUMENT_ROOT'] . '/config/config.php'); // contains db access and definitions for dynamic queries
require_once ($_SERVER['DOCUMENT_ROOT'] . '/system/functions.php'); 

$action = 'manageMaps';
$mapling_section = $_GET['mapling_section'];
$mapped_section = $_GET['mapped_section']; 
$mapped_id = $_GET['mapped_id'];

include $_SERVER['DOCUMENT_ROOT'] . '/system/documenthead.php';  // includes <body> tag as well as jquery, sorttable.js, datepicker.js
$section = $mapling_section; // so that the update button directs to the right place
?>


   <fieldset>
   	<legend><h3>Manage <?php echo $mapling_section; ?> for <?php echo type( $mapped_section ) . ' ' . $mapped_id . ': ' . sql($mapped_section, 'name', $mapped_id) ?></h3></legend>
   	
   <table class="sortable minmax">
   	<thead>
		<th>Link ID</th>
        <th><?php echo ucfirst(type($mapling_section)); ?> ID</th>
        <?php 
		if ( $mapling_section == 'photos' ) { 
			echo '<th>Photo</th>';
			echo '<th>Name</th>'; 
			echo '<th>Text short</th>';
		}
		elseif ( $mapling_section == 'locations' ) {
			echo '<th>Name</th>';
			echo '<th>Address</th>'; 
		} 
		elseif ( $mapling_section == 'hours' ) {
			echo '<th>Name</th>';
			echo '<th>Start</th>';
			echo '<th>End</th>';
		}
		else {
			echo '<th>Name</th>';
		} 
		?>
		
		<th>Options</th>
      </thead>
   	<tbody>
	<?php 
        $sql = "SELECT * FROM maps WHERE mapped_section = '$mapped_section' AND mapped_id = $mapped_id AND mapling_section = '$mapling_section' ORDER BY rank ASC ";
        $result = $db->query($sql); 
        while ($row = $result->fetch_assoc()) { 
            $id = $row['id']; 
            $mapling_id = $row['mapling_id'];
			
            $rank = $row['rank'];
            echo '<tr>';
            echo '<td><span class="comment">' . $id . '</span></td>';
			echo '<td><span class="comment">' . $mapling_id . '</span></td>';
            $sql2 = " SELECT * FROM `$mapling_section` WHERE id = '$mapling_id' ";
            $result2 = $db->query($sql2); 
            $mapling = $result2->fetch_all(MYSQLI_ASSOC); 

            if ( $mapling_section == 'photos' ) { 
				echo '<td> <a href="' . $uploaddir . $mapling[0]['file'] . '" target="_blank" ><img src="' . $uploaddir . $mapling[0]['preview'] . ' "></a></td>'; 
				echo '<td>' . $mapling[0]['name'] . '</td>';
				echo '<td>' . $mapling[0]['descr'] . '</td>';
			}
						elseif ( $mapling_section == 'locations' ) { 
				echo '<td>' . $mapling[0]['name'] . '</td>';
				echo '<td>' . $mapling[0]['street'] . ' ' . $mapling[0]['streetno'] . '<br>' . $mapling[0]['city'] . '</td>'; 
			}
			elseif ($mapling_section == 'hours' ) {
				echo '<th>' . $mapling[0]['name'] . '</th>';
				echo '<th>' . displaydate($mapling[0]['start']) . '</th>';
				echo '<th>' . displaydate($mapling[0]['end']) . '</th>';
			}
			else { 
				echo '<td>' . $mapling[0]['name'] . '</td>';
			}
            
			// options
            echo '<td>';
            echo '<form action="/system/controller/maps_changerank.php" method="post">'; // change rank
			echo '<input type="hidden" name="id" value="' . $id . '">
				<input type="hidden" name="mapped_section" value="' . $mapped_section . '">
				<input type="hidden" name="mapped_id" value="' . $mapped_id . '">
				<input type="hidden" name="mapling_section" value="' . $mapling_section . '">
				<input type="hidden" name="mapling_id" value="' . $mapling_id . '">
				<select name="newrank">';
			for ($r = 1; $r <= 99; $r++) { // loop to create rank options
				echo '<option value="' . $r . '"'; if ($rank == $r) {echo ' selected="selected" ';} echo '>Rank ' . $r . '</option>';
			}
			echo '</select> 
				<input type="submit" name="formaction" value="Change rank" />
			</form>' . PHP_EOL;
			include '../button_update.php'; // update
			echo PHP_EOL;
            echo '<form action="/system/controller/maps_delete.php" method="post">'; // remove link
			echo '<input type="hidden" name="id" value="' . $id . '">
				<input type="hidden" name="mapling_section" value="' . $mapling_section . '">
				<input type="hidden" name="mapped_section" value="' . $mapped_section . '">	
				<input type="hidden" name="mapped_id" value="' . $mapped_id . '">
				<input type="submit" name="formaction" value="Remove" /><span class="comment"> Won\'t delete, just unlink. </span>
			</form>';
			echo '</td>' . PHP_EOL;
            echo '</tr>';
        }           
     ?>
    </tbody>
	</table>
<?php 
echo '&nbsp;<br> Total results: ' . $result->num_rows . '<br></br>';  

// add more maplings
echo '<a href="/system/sections/' . $mapling_section . '.php?mapsuggest_section=' . $mapped_section . '&mapsuggest_id=' . $mapped_id . '"><button>Add '; // $mapped_section would be misleading
if ( $result->num_rows > 0 ) { echo 'more '; }
echo $mapling_section . ' to this ' . type($mapped_section) . '</button></a>';
?>

    </fieldset>

</body>
</html>