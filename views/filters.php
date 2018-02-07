<!-- <?php echo basename(__FILE__); ?>-->

<fieldset class="half">
	<legend><h3>Filters</h3></legend>
	<h4>Show all linked to &ensp;
	<select name="filter_section" onchange="filter = new FilterByItem(this);">
    	<option value="" selected="selected" disabled>Please select</option>';
		
		<?php
		// Generate dropdown of mappable sections; js will do the rest
        foreach ($sectiondata->mappable_sections as $section) {
            echo '<option value="' . $section . '">' . type($section) . '</option>' . PHP_EOL;
        }
        ?>
       
	</select>&ensp;
</h4>



</fieldset>

<?php debug ($_SESSION['filters']); ?>

<!-- <?php echo 'end ' . basename(__FILE__); ?>-->



