<?php
// display an input device to record time sheets = entries in section hours with related mappings

class viewTimesheetinput {
	function __construct($week) { 
		global $cal_weeks; 
		$currentweek = (new DateTime())->format("W");
		$currentmonth = (new DateTime())->format("m");
		$currentyear = (new DateTime())->format("Y");

		echo '<div class="msg msg_full">' . PHP_EOL;
		echo '<h2>Zapisuj nowe zadanie w tygodniu: ' . PHP_EOL;
		
		// Select week
		echo '<select id="week">' . PHP_EOL;
		foreach ( $cal_weeks as $cal_week) {
			$calweek = new calweek($cal_week); 
			$calweek->translate('polish');
			echo '<option value="' . $cal_week . '"';
			if ($cal_week == $currentweek) echo ' selected="selected"';
			if ($calweek->monthchange == true ) {
				echo ' monthchange="true"';
				echo ' startm="' . $calweek->startm . '"';
				echo ' startMonth="' . $calweek->startM . '"';
				echo ' endm="' . $calweek->endm . '"';
				echo ' endMonth="' . $calweek->endM . '"';
			}
			else echo ' month="' . $calweek->startm . '"';
			echo '>';
			echo '&ensp;' . $cal_week . ' (' . $calweek->days[1]['dateshort'] . ' - ' .  $calweek->days[7]['dateshort'] . ')';
			echo '</option>' . PHP_EOL;
		}
		echo '</select>' . PHP_EOL;
		
		// Select month (js will only show if week straddles months)
		echo ' <select id="month" class="conditional">' . PHP_EOL;
		// options will be added with js
		echo '</select>' . PHP_EOL;
		
		// Select year
		echo ' <select id="year">' . PHP_EOL;
		$year = 2017; 
		while ( $year <= $currentyear ) {
			echo '<option value="' . $year . '"';
			if ($year == $currentyear ) echo ' selected="selected"';
			echo '>';
			echo $year;
			echo '</option>' . PHP_EOL;
			$year++;
		}
		echo '</select>' . PHP_EOL;
		
		// Submit button
		echo '<button id="submitbtn" class="foolproof inactive">'; 
		echo 'Zapisuj';
		echo '</button>'; 
		echo '</h2>' . PHP_EOL;
		
		echo '<div class="sep_horiz"></div>' . PHP_EOL;
		
		echo '<div class="container">' . PHP_EOL; 
		
		// *** 1st selection
		echo '<div id="selectType" class="box">' . PHP_EOL;
		echo '<h2>Co zrobiłeś?</h2>';
		
		echo '<div>';
		// Project work
		echo '<button class="foolproof interview" type="project"  account="52">'; 
		echo 'Pracę dla projektu'; 
		echo '</button>' . PHP_EOL;
		
		// Non-project work
		echo '<button class="foolproof interview" type="nonproject">'; 
		echo 'Pracę nie dotyczącą projektu'; 
		echo '</button>' . PHP_EOL;
		
		$listNonwork = new listItems('accounts', 'nonwork'); 
		
		foreach ( $listNonwork->items as $nonwork ) {
			echo '<button class="foolproof small interview" type="nonwork" account="' . $nonwork->id . '">'; 
			echo $nonwork->columns['descr']; 
			echo '</button>' . PHP_EOL;
		}
		
		echo '</div></div>' . PHP_EOL;
		
		
		// *** 2nd selection if project
		echo '<div id="selectProject" class="box conditional">' . PHP_EOL;
		echo '<h2>Wybierz projekt:</h2>' . PHP_EOL;
		
		$listProjects = new listItems('projects', 'active'); 
		echo '<div>';
		foreach ($listProjects->items as $project) {
			echo '<button class="foolproof interview" type="selectProject" project="' . $project->id . '">'; 
			echo $project->columns['name']; 
			echo '</button>' . PHP_EOL;
		}
		
		echo '</div></div>' . PHP_EOL;
		
		// *** 2nd selection if non-project
		echo '<div id="selectAccount" class="box conditional">' . PHP_EOL;
		echo '<h2>Wybierz zakres:</h2>' . PHP_EOL;
		
		$listAccounts = new listAccounts('timesheet'); 
		echo '<div>';
		foreach ($listAccounts->accounts as $account) {
			echo '<button class="foolproof interview" type="selectAccount" account="' . $account['id'] . '">'; 
			
			echo $account['name']; 
			echo '</button>' . PHP_EOL;
		}
		echo '</div></div>' . PHP_EOL;
		
		// *** 3rd selection
		echo '<div id="selectCarpart" class="box conditional">' . PHP_EOL;
		echo '<h2>Wybierz zakres:</h2>' . PHP_EOL;
		
		$listCarparts = new listItems(carparts, '', 'rank');
		echo '<div>'; 
		foreach ($listCarparts->items as $carpart) {
			echo '<button class="foolproof interview" type="selectCarpart" carpart="' . $carpart->id . '">'; 
			echo $carpart->columns['name_pl']; 
			echo '<p class="tiny mouseover">' . $carpart->columns['descr'] . '</p">';
			echo '</button>' . PHP_EOL;
		}
		echo '</div></div>' . PHP_EOL;
		
		// *** 5th selection
		echo '<div id="selectHours" class="box conditional">' . PHP_EOL;
		echo '<h2>Ilość godzin:</h2>' . PHP_EOL;
		
		echo '<h2>' . PHP_EOL;
		echo '<select id="time" name="time">' . PHP_EOL;
		echo '<option selected="selected" disabled></option>';
		
		for ( $t=1; $t<41; $t++) {
			echo '<option value="' . $t . '">';
			echo $t;
			echo '</option>' . PHP_EOL;
		}
		echo '</select>' . PHP_EOL;
		echo '</h2>' . PHP_EOL;
		
		echo '<h2>Komentarz:</h2>' . PHP_EOL;
		
		echo '<h2>' . PHP_EOL;
		echo '<textarea id="descr" placeholder="Opcjonalnie - wypełniać kiedy pomocny.">' . PHP_EOL;
		echo '</textarea>' . PHP_EOL;
		echo '</h2>' . PHP_EOL;
		
		echo '</div>' . PHP_EOL;
		
		echo '</div></div>' . PHP_EOL;											
		
	}
}