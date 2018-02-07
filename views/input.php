<!-- <?php echo basename(__FILE__); ?>-->
<fieldset class="half">
<legend>
<?php 
//Can be used to create new records, or to update existing ones. Displays for each section only the columns defined in 'db.php'.
// If used for updating, we need a new item object before.

if ($action == 'update') { echo '<h3>Update information for ' . type($section) . ' ' . $id . '</h3></legend>'; } 
else { echo '<h3>Save new ' . type($section) . '</h3></legend>'; } 

?>
<form class="input" method="post" action="/system/controller/save.php" >
<input type="hidden" name="action" value="<?php echo $action; ?>" />
<input type="hidden" name="section" value="<?php echo $section; ?>" />
<?php if ($action == 'update') echo '<input type="hidden" name="action" value="' . $action . '">'; ?>
<input type="hidden" name="id" value="<?php echo $id; ?>" />
	<table>
        <tbody>
        <?php foreach ( $sectiondata->columns as $column ) {
			echo '<tr>';
			switch ($type[$column]['type']) {
				case 'cal_week': 
					$currentweek = (new DateTime())->format("W");
					echo '<td>' . $type[$column]['label'] . '</td>';
					echo '<td> <select name="' . $column . '"><option value="" selected="selected">Please select</option>';
					$array = $type[$column];
					foreach($array['dropdown'] as $dropdown ) { 
						echo '<option value="' . $dropdown . '"';
						if ( $dropdown == $currentweek-1 ) echo ' selected="selected"';
						echo '>' . $dropdown . '</option>' . PHP_EOL;
					}
					echo '</select><br></td>'; 
					echo '<td></td>' . PHP_EOL;
					break; 
				
				case 'datetime': 
					echo '<td>' . $type[$column]['label'] . '</td>';
					echo '<td><input type="text" name="' . $column . '"'; 
					if ($action == 'update') echo 'value="' . $item->columns[$column] . '"';
					echo '/>';
					echo '<script type="text/javascript">' . PHP_EOL; 
					echo "$(function(){ $('*[name=" . $column . "]').appendDtpicker(); });" . PHP_EOL;
					echo '</script>' . PHP_EOL;
					echo '</td><td></td>' . PHP_EOL;
					break; 
					
				case 'dropdown': 
					echo '<td>' . $type[$column]['label'] . '</td>';
					echo '<td> <select name="' . $column . '"><option value="" selected="selected">Please select</option>';
					$array = $type[$column];
					foreach($array['dropdown'] as $dropdown ) { 
						echo '<option value="' . $dropdown . '"';
						if ( $dropdown == $item->columns[$column] ) echo ' selected="selected"';
						echo '>' . $dropdown . '</option>' . PHP_EOL;
					}
					echo '</select><br></td>'; 
					echo '<td></td>' . PHP_EOL;
					break; 
					
				case 'file': 
					echo '<td>' . $type[$column]['label'] . '</td>';
					echo '<td><a class="bigimg" href="'.$uploaddir.$item->columns[$column].'" target="_blank"><img src="'.$uploaddir.$item->preview.'"></a>';
					if ($action == 'update') echo '<input type="hidden" name="file" value="' . $item->columns[$column] . '">'; // file can't be changed
					echo '</td><td></td>' . PHP_EOL;
					break; 
				
				case 'month': 
					$lastweeksmonth = date('m', strtotime('last week')); // (new DateTime())->format("W");
					echo '<td>' . $type[$column]['label'] . '</td>';
					echo '<td> <select name="' . $column . '"><option value="" selected="selected">Please select</option>';
					$array = $type[$column];
					foreach($array['dropdown'] as $dropdown ) { 
						echo '<option value="' . $dropdown . '"';
						if ( $dropdown == $lastweeksmonth ) echo ' selected="selected"';
						echo '>' . $dropdown . '</option>' . PHP_EOL;
					}
					echo '</select><br></td>'; 
					echo '<td></td>' . PHP_EOL;
					break; 				case 'tags': 
					echo '<td>' . $type[$column]['label'] . '</td>';
					echo '<td><input type="text" name="' . $column . '" placeholder="' . $type[$column]['placeholder'] . '"';
					if ($action == 'update') echo 'value="' . $item->columns[$column] . '"';
					echo '/><td><span class="comment">Use single words, separate by space. Used so far:</span><br>'.PHP_EOL;
					// list existing ones
					echo implode($sectiondata->tags[$section], '&emsp;');
					break; 
				
				case 'number': 
					echo '<td>' . $type[$column]['label'] . '</td>';
					echo '<td><select name="rank">';
					for ($r = 1; $r <= 99; $r++) { // loop to create rank options
						echo '<option value="' . $r . '"'; if ($item->columns['rank'] == $r) echo ' selected="selected" '; echo '>' . $r . '</option>';
					}
					echo '</select><td><span class="comment">Identical ranks of other '. $section . ' will be pushed down.<br></td>' . PHP_EOL;
					break; 
					
				case 'option': 
					$table = $type[$column]['option']; // what table to feed into dropdown  
					echo '<td>' . $type[$column]['label'] . '</td>';
					echo '<td> <select name="' . $column . '"><option value="" selected="selected">Please select</option>';
					$sql = "SELECT * FROM `$table` ORDER BY name ASC"; 
					$result = $db->query($sql);
					while($row = $result->fetch_assoc()){
						echo '<option value="' . $row['id'] . '"';
						if ( $row['id'] == $item->columns[$column] || $row['id'] == $filter_id ) { echo ' selected="selected"'; }
						echo '>' . $row['name'] . '</option>' . PHP_EOL;
					}
					echo '</select></td>'; 
					echo '<td></td>' . PHP_EOL;
					break;
				
				case 'textarea': 
					echo '<td>' . $type[$column]['label'] . '</td>';
					echo '<td><textarea name="' . $column . '">'; 
					if ($action == 'update') echo $item->columns[$column];
					echo '</textarea></td>'; 
					echo '<td><span class="comment">Drag corner to change size.<br></td>' . PHP_EOL;
					break; 
					
				case 'yesno': 
					echo '<td>' . $type[$column]['label'] . '</td>';
					echo '<td><input type="checkbox" name="' . $column . '" value = "1"'; 
					echo ( $action !== 'update'  &&  $item->columns[$column] == 1 )? ' checked ="checked"' : '';
					echo '/></td><td></td>' . PHP_EOL;
					break;
				
				default: 
					echo '<td>' . $type[$column]['label'] . '</td>';
					echo '<td><input type="text" name="' . $column . '" placeholder="' . $type[$column]['placeholder'] . '"';
					if ($action == 'update') echo 'value="' . $item->columns[$column] . '"'; 
					echo '/><td></td>' . PHP_EOL;
				}
				echo '</tr>';
			}
       
        ?>
        </tbody>
    </table>

<?php echo '<input type="submit" value="'. (($action == 'update') ? 'Update' : 'Save') . '">'; ?>
	
</form>
<?php if ($action == 'update') echo '<a href="/system/pages/sectionview.php?section=' . $section . '"><button>Back to overview</button></a>'; ?>
</fieldset>
<!-- <?php echo 'end ' . basename(__FILE__); ?>-->



