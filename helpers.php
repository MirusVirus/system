<?php 
function debug ($var) {
// Prints the content of a variable. Economizes writing the pre tags etc.
	echo '<pre style="font-family:courier; font-size:12px">Debug:<br>';
	print_r ($var); 
	echo '</pre><br>';
}

function remove_http($url) {
	/* as the name says */ 
   $disallowed = array('http://', 'https://', 'http://www.', 'https://www.', 'www.');
   foreach($disallowed as $d) {
      if(strpos($url, $d) === 0) {
         return str_replace($d, '', $url);
      }
   }
   return $url;
}

function prettyHTTP($url) {
	global $urllength; 
	// remove prefixes
	$disallowed = array('http://', 'https://', 'http://www.', 'https://www.', 'www.');
	foreach($disallowed as $d) {
		if(strpos($url, $d) === 0) {
		$url = str_replace($d, '', $url);
      }
   }
   // shorten
   if (strlen($url) > 20) $url = substr($url, 0, 20).'...';
   
   return $url;
}

function type($section) {
	/* we need this so often ... */ 
   $type = substr ($section, 0, -1);
   return $type;
}

function displaydate( $datestring ) {
	/* beautifies a datetime coming from db */
	if ($datestring) {
		$date = new DateTime( $datestring );
		$day = $date->format ('M\&\n\b\s\p;d,\&\n\b\s\p;Y'); // need to escape characters for &nbsp;
		$time = $date->format ('H:i');
		$displaydate = $day . ' ' . $time;
		
		return $displaydate; 
	}
}

function flattenArray($array, $return=array()) { 
	/* transforms a multidimensional array of unknown structure into a one-dimensional array with numeric keys */
    foreach ($array as $key => $value) {
		if(is_array($value)) {
			$return = flattenArray($value,$return);
		}
		else {
			if($value) {
				$return[] = $value;
			}
		}
	}
	return $return;
}

?>