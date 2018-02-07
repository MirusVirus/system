<?php
class viewMaplings {
	function __construct($maplings) {
		foreach ($maplings->items as $mapling) $this->html.= (new viewShortieMap($mapling))->html; 	
	}
}