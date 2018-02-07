<?php
class viewMapped {
	function __construct($mapped) {
		foreach ($mapped->items as $mapped) $this->html.= (new viewShortieMap($mapped))->html;
	}
}
?>