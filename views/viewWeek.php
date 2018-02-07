<?php
// display a week for those who are completing time sheets, 
// so they knwo what's already there

class viewWeek {
	function __construct($week) { 
		
		echo $week['complete'] == 1 ? '<div class ="tile">' : '<div class ="tile meep">' ; 
		
		// the preview
		echo '<h3>Tydzien ' . $week['week']; 
		if ( $week['complete'] == 0 ) echo '<span class="hm">- proszę uzupełniaj</span>'; 
		echo '</h3>';
		echo '<p>Zadania: ' . $week['jobcount'] . '<br>';
		echo 'Saldo: ' . $week['total'] . ' h</p>';
		if ( $week['jobcount'] > 0 ) {
			 echo '<button class="morebtn">zobacz więcej</button>';
			// echo '<button class="lessbtn">mnej</button>';
		
			// view more
			echo '<div class="more">';
			// debug ($week); 
			foreach ( $week['jobs']  as $jobid ) {
				$job = new  job($jobid); 
				echo (new viewJob($job))->html;  
				// debug($job);
			}
			echo '</div>';
		}
		
		echo '</div>';
																
		
	}
}