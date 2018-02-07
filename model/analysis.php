<?php

// Analyses a section that has an "amount" column. 
// The amounts are added and filtered depending on which items they are mapped to. 
// var $section - the section that will be analyzed
// var $item - optionally, only amounts that are linked to this item will be included.

class analysis {
	function __construct($section, $item){
		
		global $db, $sectionconfig, $timestart;
		$this->section = $section; 
		if ($item) $this->item = $item;
		
		// prepare filters (all mappable sections will be filters)
		$this->filters= array(); 
		foreach ( $sectionconfig[$this->section]['map'] as $filtersection ) {
			if ( $filtersection != $this->item->section ) $this->filters[$filtersection] = array(); 
		};
		
		// prepare count array	
		$this->count = array(); 
		$this->count['total'] = 0;
		foreach ($this->filters as $filtersection => $value ) $this->count[$filtersection] = array(); 
		$this->complete = 1; 
		
		// get mappings of $item if $item is given XXXXX need to straighten this XXXXX
		
		if (!$this->item) $sql_maps = "SELECT * FROM maps WHERE mapling_section = '$this->section'"; 
		else $sql_maps = "SELECT * FROM maps WHERE mapped_section = '{$this->item->section}' AND mapped_id = {$this->item->id} AND mapling_section = '$this->section'"; 
		$result_maps = $db->query($sql_maps); 
		$maps = $result_maps->fetch_all(MYSQLI_ASSOC);
		
		// get maplings (items behind the mappings)
		foreach ($maps as $map) {
			$id = $map['mapling_id'];
			$sql_maplings = "SELECT * FROM $this->section WHERE id = $id";
			$result_maplings = $db->query($sql_maplings);
			$mapling = $result_maplings->fetch_assoc();									// entire mapling data
			$this->maplings[$id] = $mapling;											// add mapling to object
			
			// sum up
			$this->count['total'] += $mapling['amount'];
						
			// see what the maplings are mapped to (other than the analyzed item) to produce filters
			foreach ( $this->filters as $filtersection => $items ) {
				$mapped_id = getFirstMapped($filtersection, $this->section, $id); 
				
				if ( $mapped_id ) { // not all maplings are mapped to all filter sections
					// add mapping information to mapling
					$this->maplings[$id][$filtersection]['id'] = $mapped_id;
					$this->maplings[$id][$filtersection]['name'] = sql($filtersection, 'name', $mapped_id); 
					
					// adjust filters
					if ( !in_array( $mapped_id, $items )) array_push( $this->filters[$filtersection], $mapped_id ); 
					
					// update counts
					if ( $mapling ) ; 
					// $this->count[$filtersection][$mapped_id] += $mapling['amount'];
				}
			}	
		}
	}
	
	// Prepare data on weekly and monthly basis
	function analyzeTimeline() {
		global $timestart; 
		$now = new DateTime; 															
		$now->modify('+1 week'); 														// add a week, so the current week is not cut off
		

		// weekly data
		$this->weeklyAnalysis = array(); 
		$start = clone $timestart;
		
		while ( $start < $now ) {
			$Y = $start->format("Y");
			$w = $start->format("W"); 
			
			// total
			$this->weeklyAnalysis[$Y.'-'.$w]['week'] = $Y.'-'.$w;
			$this->weeklyAnalysis[$Y.'-'.$w]['total'] = 0;								// amount of hours worked
			$this->weeklyAnalysis[$Y.'-'.$w]['jobcount'] = 0;							// amount of jobs recorded	
			$this->weeklyAnalysis[$Y.'-'.$w]['jobs'] = array();							// list of jobs recorded
			
			foreach ( $this->maplings as $id => $data ) {								// loop through mapped hours
				if ( $data['year'] == $Y && $data['cal_week'] == $w ) {
					$this->weeklyAnalysis[$Y.'-'.$w]['total'] += $data['amount'];
					$this->weeklyAnalysis[$Y.'-'.$w]['jobcount'] += 1;
					$this->weeklyAnalysis[$Y.'-'.$w]['jobs'][] = $id;
				}
			}
			
			// split by filters
			foreach ($this->filters as $filtersection => $items ) {
				foreach ( $items as $item ) {
					$this->weeklyAnalysis[$Y.'-'.$w][$filtersection][$item] = 0;
					foreach ( $this->maplings as $mapling ) {						
						if ( $mapling['year'] == $Y && $mapling['cal_week'] == $w && $mapling[$filtersection]['id'] == $item ) {
							$this->weeklyAnalysis[$Y.'-'.$w][$filtersection][$item] += $mapling['amount'];
						}
					}
				}
			}
			$start->modify("+1 week");
		}	
		
		// monthly data
		$this->monthlyAnalysis = array(); 
		$start = clone $timestart;
		
		while ( $start < $now ) {
			$Y = $start->format("Y"); 
			$m = $start->format("m");
			
			// total
			foreach ( $this->maplings as $mapling ) {
				if ( $mapling['year'] == $Y && $mapling['month'] == $m ) {
					$this->monthlyAnalysis[$Y.'-'.$m]['total'] += $mapling['amount'];
				}
			}
			// split by filters
			foreach ($this->filters as $filtersection => $items ) {
				foreach ( $items as $item ) {
					foreach ( $this->maplings as $mapling ) {
						if ( $mapling['year'] == $Y && $mapling['month'] == $m && $mapling[$filtersection]['id'] == $item ) {
							$this->monthlyAnalysis[$Y . '-' .$m][$filtersection][$item] += $mapling['amount'];
						}
					}
				}
			}
			$start->modify("+1 month");
		}	
	} 
	
	// Check whether weeks are complete, i.e. weekly hours meet $weeklyhours
	
	function checkWeeks() {
		global $weeklyhours; 
		
		foreach ( $this->weeklyAnalysis as &$week ) {
			if ( $week['total'] < $weeklyhours ) {
				$week['complete'] = 0; 							// set this week incomplete
				$this->complete = 0; 							// set entire analysis to incomplete
			}
			else $week['complete'] = 1;
				
		}
	}
	
}
?>