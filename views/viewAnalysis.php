<?php

class viewAnalysis {
	function __construct($analysis) {
		
		// By calendar week //
		
		$this->html = '<fieldset><legend><h3>Hours worked - by week</h3></legend>' . PHP_EOL; 
		$this->html.= '<span class="comment">Click table head labels to sort.</span>' . PHP_EOL;
		// Table head
		$this->html.= '<table class="sortable">' . PHP_EOL; // minmax = expand rows on click
		$this->html.= '<thead>' . PHP_EOL;
		$this->html.= '<tr>';
		 
		$this->html.= '<th>Hours for cal week</th>'; 
		$this->html.= '<th>Total</th>';
		
		foreach ( $analysis->filters as $filtersection => $items ) {
			foreach ( $items as $item ) {
				$this->html.= '<th>' . sql($filtersection, 'name', $item) . '</th>';
			}
		}		 
		$this->html.= '</tr>' . PHP_EOL;
		$this->html.= '</thead>' . PHP_EOL;
		
		// Table body
		$this->html.= '<tbody>';
		foreach ( $analysis->weeklyAnalysis as $week => $data) {
			$this->html.= '<tr >';
			$this->html.= '<td style="background:rgb(80,80,80); color:white; font-weight:bold;"><span >' . $week . '</span></td>';
			// Total per week
			$this->html.= '<td>' . $data['total'] . '</td>';
			// By filters
			foreach ( $analysis->filters as $filtersection => $items ) {
				foreach ( $items as $item ) {
					$a = 0.5 * ($data[$filtersection][$item] / $data['total']); 								// opacity value
					$this->html.= '<td style="background: rgba(0,88,122,' . $a . ')">' . $data[$filtersection][$item] . '</td>';
				}
			}	
			$this->html.= '</tr>';
		}
		// Total
		$this->html.= '<tr>';
		$this->html.= '<th style="background:rgb(60,60,60);  color:white;">Total</th>';
		$this->html.= '<th style="background:rgb(210,210,210); ">' . $analysis->count['total'] . '</th>';
		foreach ( $analysis->filters as $filtersection => $items ) {
				foreach ( $items as $item ) {
					$a = 0.1 + (0.5 * ($analysis->count[$filtersection][$item] / $analysis->count['total']));	// opacity value
					$this->html.= '<th style="background:rgba(10,40,70,' . $a . '); ">' . $analysis->count[$filtersection][$item] . '</th>';
				}
			}	
		$this->html.= '</tr>';
		
		$this->html.= '</tbody>';
		$this->html.= '</table><br>';
				
		$this->html .= '</fieldset>' . PHP_EOL; 
		
		
		// By month //
		$this->html.= '<fieldset><legend><h3>Hours worked - by month</h3></legend>' . PHP_EOL; 
		
		// Table head
		$this->html.= '<span class="comment">Click table head labels to sort.</span>' . PHP_EOL;
		$this->html.= '<table class="sortable">' . PHP_EOL; // minmax = expand rows on click
		$this->html.= '<thead>' . PHP_EOL;
		$this->html.= '<tr>';
		 
		$this->html.= '<th>Hours for month</th>'; 
		$this->html.= '<th>Total</th>';

		foreach ( $analysis->filters as $filtersection => $items ) {
			foreach ( $items as $item ) {
				$this->html.= '<th>' . sql($filtersection, 'name', $item) . '</th>';
			}
		}		 
		$this->html.= '</tr>' . PHP_EOL;
		$this->html.= '</thead>' . PHP_EOL;
		
		// Table body
		$this->html.= '<tbody>';
		foreach ( $analysis->monthlyAnalysis as $month => $data) {
			$this->html.= '<tr>';
			$this->html.= '<td style="background:rgb(80,80,80); color:white; font-weight:bold;">' . $month . '</td>';
			// Total
			$this->html.= '<td>' . $data['total'] . '</td>';
			// By filters
			foreach ( $analysis->filters as $filtersection => $items ) {
				foreach ( $items as $item ) {
					$a = 0.5 * ($data[$filtersection][$item] / $data['total']); 								// opacity value
					$this->html.= '<td style="background: rgba(0,88,122,' . $a . ')">' . $data[$filtersection][$item] . '</td>';
				}
			}	
			$this->html.= '</tr>';
		}
		
		// Total
		$this->html.= '<tr>';
		$this->html.= '<th style="background:rgb(60,60,60); color:white">Total</th>';
		$this->html.= '<th style="background:rgb(210,210,210); ">' . $analysis->count['total'] . '</th>';
		foreach ( $analysis->filters as $filtersection => $items ) {
				foreach ( $items as $item ) {
					$a = 0.1 + (0.5 * ($analysis->count[$filtersection][$item] / $analysis->count['total'])); 	// opacity value
					$this->html.= '<th style="background:rgba(10,40,70,' . $a . '); ">' . $analysis->count[$filtersection][$item] . '</th>';
				}
			}	
		$this->html.= '</tr>';
		
		$this->html.= '</tbody>';
		$this->html.= '</table>';
				
		$this->html .= '</fieldset>' . PHP_EOL; 
	}
}
?>