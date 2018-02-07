<?php 
// Prepares the dates of a calendar week
// var $week = calendar week number
// var $year = year; if no year given, the current year is assumed

class calweek { 																				// 
	function __construct($week) {
		
		$this->week = $week;
		$this->year = (new DateTime)->format('Y');
		$this->days = array();
		
		for ( $d = 1; $d <= 7; $d++) {
			$this->days[$d]['date'] = new DateTime();
			$this->days[$d]['date']->setISODate($this->year, $this->week, $d);
			$this->days[$d]['datestring'] = $this->days[$d]['date']->format('d.m.Y');
			$this->days[$d]['dateshort'] = $this->days[$d]['date']->format('d.m');
			$this->days[$d]['weekday'] = $this->days[$d]['date']->format('l');
			$this->days[$d]['day'] = $this->days[$d]['date']->format('d');
			$this->days[$d]['month'] = $this->days[$d]['date']->format('m');
			$this->days[$d]['monthname'] = $this->days[$d]['date']->format('F');
		}
		
		$this->startm = $this->days[1]['date']->format('m');
		$this->endm = $this->days[7]['date']->format('m');
		$this->startM = $this->days[1]['date']->format('M');
		$this->endM = $this->days[7]['date']->format('M');
		$this->monthchange = ( $this->startm == $this->endm ) ? false : true;  
	}
	
	function translate($language) {
		
		$this->germanmonths = array ( 
			'01' => 'Januar',  
			'02' => 'Februar', 
			'03' => 'März', 
			'04' => 'April', 
			'05' => 'Mai', 
			'06' => 'Juni', 
			'07' => 'Juli', 
			'08' => 'August', 
			'09' => 'September', 
			'10' => 'Oktober', 
			'11' => 'November',
			'12' => 'Dezember'  
		); 
		
		$this->germandays = array ( 
			1 => 'Montag',  
			2 => 'Dienstag', 
			3 => 'Mittwoch', 
			4 => 'Donnerstag', 
			5 => 'Freitag', 
			6 => 'Samstag', 
			7 => 'Sonntag' 
		); 
		
		$this->polishmonths = array (
			'01' => 'styczeń',  
			'02' => 'luty', 
			'03' => 'marzec', 
			'04' => 'kwiecień', 
			'05' => 'maj', 
			'06' => 'czerwiec', 
			'07' => 'lipiec', 
			'08' => 'sierpień', 
			'09' => 'wrzesień', 
			'10' => 'październik', 
			'11' => 'listopad',
			'12' => 'grudzień'  
		); 
		
		$this->polishdays = array (
			1 => 'poniedziałek',	// Nachsonntagling
			2 => 'wtorek', 			// Zweitling	
			3 => 'środa', 			// Mitte
			4 => 'czwartek', 		// Viertling
			5 => 'piątek', 			// Fünftling
			6 => 'sobota', 			// Sabbat
			7 => 'niedziela'  		// Nicht-Tuer
		); 
		
		for ( $d = 1; $d <= 7; $d++) {
			$this->days[$d]['weekday'] = $this->{$language . 'days'}[$d];
			$this->days[$d]['monthname'] = $this->{$language . 'months'}[$this->days[$d]['month']];
		}
		
		$this->startM = $this->{$language . 'months'}[$this->startm];
		$this->endM = $this->{$language . 'months'}[$this->endm];
	}
	
	
}
?>