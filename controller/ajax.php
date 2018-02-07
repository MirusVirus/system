<?php 
session_start();
require_once ($_SERVER['DOCUMENT_ROOT'] . '/system/autoload.php');
// *All* AJAX calls call this document. 
// The response is a JSON-encoded array with three elements: message (always), data (optional), html (optional).

$message = array(); 																// will collect all messages from AJAX switch

$ajax = json_decode (file_get_contents('php://input'), true);						// php://input collects kind of the raw data!?

switch ($ajax['action']) { 															// let's see what kind of request comes in
	/*
	case 'addFilter': 																// adds filter to $_SESSION and responds with html filter button
		$data = new filter($ajax['data']); 
		$html = (new viewFilter($data->data))->html; 
		$message[] = 'Set filter'; 
		break; 
	*/
	case 'checkAJAX': 																// testtest
		$message[] = 'AJAX is working just fine!'; 
		break; 
		
	case 'confirm': 																// Confirm a user who has registered
		if ( $ajax['data']['section'] == 'users' && !sql('users', 'role', $ajax['data']['id'])) {
			$message[] = '<span class="uh">Please select a role for this user first.</span>';
		}
		else {
			$message[] = confirm($ajax['data']['section'], $ajax['data']['id']); 	// change status to "confirmed"
			
			// use PHPMailer for confirmation email
			$email = sql($ajax['data']['section'], 'email', $ajax['data']['id']); 	// retrieve email address
			require($_SERVER['DOCUMENT_ROOT'] . "/system/res/php/PHPMailerAutoload.php"); // autoload mailer class
			$mail = new PHPMailer;
			$mail->addAddress($email);     											// Add a recipient
			$mail->Subject = $title . ': ' . ${'confirm_'.$ajax['data']['section'].'_subject'};
			$mail->Body    = ${'confirm_'.$ajax['data']['section'].'_mailbody'};
			
			if(!$mail->send()) {
				$message[] = 'Message could not be sent.';
				$message[] = 'Mailer Error: ' . $mail->ErrorInfo;
			} else {
				$message[] = 'Sent confirmation message to ' . $email;
			}
		}
		break; 
	
	case 'copy': 																	// create a copy  in another section
		$data = new duplicate($ajax['data']['section'], $ajax['data']['id'], $ajax['data']['copy_section']);
		if ( $ajax['data']['recreateLinks'] == true ) $data->recreateLinks();		// recreates all mappings of the source item on target item
		// if ( $ajax['data']['linkCopy'] == true ) $message[] = 'Link copy'; 
		if ( $ajax['data']['linkCopy'] == true ) $data->linkCopy(); 				// creates a mapping btw source & target item
		$message[] = $data->message;
		break; 

	case 'copy_input': 																// fill dialogue with sections
		$data = $_SESSION['login']['perm_sections'];
		break; 
		
	case 'delete': 																	// delete any item
		$data = new delete($ajax['data']['section'], $ajax['data']['id']); 
		$message = $data->message;
		break; 
		
	case 'duplicate': 																// create an identical copy in the same section
		$data = new duplicate($ajax['data']['section'], $ajax['data']['id'], $ajax['data']['section']); 
		$message[] = $data->message;
		break; 
		
	case 'getMaplings': 															// gets all maplings mapped to this item
		$data = new maplings($ajax['data']['section'], $ajax['data']['id'], $ajax['data']['mapling_section']); 
		$html = (new viewMaplings($data))->html; 									// to be inserted after AJAX call
		break; 
		
	case 'getMapped': 																// gets all items this one is mapped to 
		$data = new mapped($ajax['data']['section'], $ajax['data']['id'], $ajax['data']['mapped_section']); 
		$html = (new viewMapped($data))->html; 										// to be inserted after AJAX call
		break; 
	
	case 'graph1': 																	// data for graph 1 view (springy.js)
		$data = new graph1($ajax['data']['section'], $ajax['data']['id']); 
		$message = 'Send graph1 data for ' .type($ajax['data']['section']) . ' ' . $ajax['data']['id']; 
		break; 
		
	case 'graph2': 																	// data for graph 1 view (springy.js)
		$data = new graph2($ajax['data']['section'], $ajax['data']['id']); 
		$message = 'Send graph2 data for ' .type($ajax['data']['section']) . ' ' . $ajax['data']['id']; 
		break; 
		
	case 'filter_input': 															// get a list with mapping targets
		$data = new allMappable($ajax['data']['mapped_section']); 
		break; 
		
	case 'lang': 															// get a list with mapping targets
		$data = new lang($ajax['data']['lang']); 
		$message[] = $data->message;
		break; 
		
	case 'map1_input': 																// get a list with mapping targets
		$data = new allMappable($ajax['data']['mapped_section']); 
		$data->addTags();
		break; 
	
	case 'map2_input': 																// fill dialogue with maplings 
		$data = new allMaplings($ajax['data']['mapling_section']); 
		break; 	

	case 'map1': 																	// this is the mapling and will be mapped to another section
		$data = new map($ajax['data']['mapped_section'], $ajax['data']['mapped_id'], $ajax['data']['section'], $ajax['data']['id'], $ajax['data']['rank']);
		$data->getMappedShortie(); 
		$html = (new viewShortieMap($data->items[0]))->html; 
		$message[] = $data->message; 
		break; 
		
	case 'map2': 																	// a mapling from another section will be mapped to this
		$data = new map($ajax['data']['section'], $ajax['data']['id'], $ajax['data']['mapling_section'], $ajax['data']['mapling_id'], $ajax['data']['rank']);
		$short = sql($data->mapling_section, $sectionconfig[$data->mapling_section]['short'][0], $data->mapling_id); 
		$html = (new viewShortieMap (new shortie ( $data->mapling_section, $data->mapling_id, $short, $data->id)))->html;
		$message[] = $data->message; 
		break; 
		
	case 'print': 																	// adds item to print queue
		$data = new printItem($ajax['data']['section'], $ajax['data']['id']); 		
		$message[] = $data->message; 
		break; 
		
	
	case 'processNewuploads': 														// process photos in folder images/newuploads
		$uploadfiles = array_diff(scandir($_SERVER['DOCUMENT_ROOT'] . $newuploaddir), array('.', '..'));
		$message[] = 'Found ' . count($uploadfiles) . ' file(s) to process.';
		$i = 0; 
		foreach ( $uploadfiles as $uploadfile ) {
			$image = new image($_SERVER['DOCUMENT_ROOT'] . $newuploaddir . '/' . $uploadfile, $uploadfile, '', '', ''); 
			$image->process();
			$message[] = $image->message;
			$image = NULL; 															// free memory
			if(++$i > $batch) break; 												// limit to x loops defined in config.php, to avoid server choke
		}
		$uploadfiles = array_diff(scandir($_SERVER['DOCUMENT_ROOT'] . $newuploaddir), array('.', '..'));
		$message[] = ( count($uploadfiles) < 1 ) ? $newuploaddir . ' is now empty.' : 'Not all files could be processed';
		$message[] = '<span class="go">Reload the page to see new photos.</span>';
		$message[] = $msgbreak;
		break; 
		
	case 'resetMsg': 																// resets messages
		$_SESSION['message'] = '';
		$_SESSION['message'] = array();	
		break; 
		
		
	case 'search_input': 															// search in db
		break; 
	
	case 'search': 																	// search in db
		$message[] = 'You were searching for: "'. $ajax['data']['string'] . '"';
		$data = new search($ajax['data']['string'], $ajax['data']['section']);
		$html = (new viewSearch($data))->html; 
		break; 
	
	case 'tag': 																	// adds string to tag string
		$message[] = tag($ajax['data']['section'], $ajax['data']['id'], $ajax['data']['tag']);
		$html = (new viewTag($ajax['data']['section'], $ajax['data']['id'], $ajax['data']['tag']))->html; // to be inserted after AJAX call
		break; 
		
	case 'timesheet': 																// creates a new entry in hours and does the mapping work
		$data = new saveItem($ajax['data']['columns']);
		$data->process();
		$message[] = $data->message;
		foreach ( $ajax['data']['maps'] as $section => $id ) {
			$map = new map($section, $id, $ajax['data']['columns']['section'], $data->id, 1);
			$message[] = $map->message;
		}
		break; 
		
	case 'timesheetDelete': 
		$data = new delete($ajax['data']['section'], $ajax['data']['id']); 
		$message = $data->message;
		break; 
		
	case 'timesheetCopy': 
		$data = new duplicate($ajax['data']['section'], $ajax['data']['id'], $ajax['data']['section']);
		$data->recreateLinks();														// recreates all mappings of the source item on target item 
		$message[] = $data->message;
		break;
		
	case 'unfilter': 																// removes one filter
		$section = $ajax['data']['section'];
		$key = $ajax['data']['key'];
		unset ($_SESSION['filters'][$section][$key]);
		$data = $ajax['data'];
		$message[] = 'Removed filter';
		break; 
		
		
	case 'unfilterAll':																// removes all filters
		$section = $ajax['data']['section'];
		unset ($_SESSION['filters'][$section]);
		$message[] = 'Removed all filters from section "' . $section . '"';
		break; 
		
	case 'unmap': 																	// deletes a mapping
		$message[] = unmap($ajax['data']['map_id']);
		break; 
		
	case 'unprint': 																// removes an item from the printing queue
		$section = $ajax['data']['section'];
		$key = $ajax['data']['key'];												// the key of that item in $_SESSION['print'][$section]
		unset ($_SESSION['print'][$section][$key]);
		$message[] = 'Removed ' . type($section) . ' ' . $ajax['data']['id'] . ' from print queue';
		break; 
		
	case 'untag': 																	// removes string from tag string
		$message[] = untag($ajax['data']['section'], $ajax['data']['id'], $ajax['data']['tag']);
		$html = (new viewButton('tag', $ajax['data']['id'], $ajax['data']['tag']))->html;
		break; 
		
	case 'updateValue':
		$data = new updateValue($ajax['data']); 									// updates one single value in a data row
		$data->fixRank();															// check if other items have same rank; if so, push their ranks down 
		$message[] = $data->message;
		break; 
	
	case 'upload': 																	// removes string from tag string
		$message[] = 'upload';
		break; 
		
	case 'urlphoto': 																// create a new photo from url and map it to this
		// create photo
		copy($ajax['data']['url'], $_SERVER['DOCUMENT_ROOT'] . $tmpimg . 'tmp');	// save file from url in temp directory
		$image = new image($_SERVER['DOCUMENT_ROOT'].$tmpimg.'tmp', basename($ajax['data']['url']), basename($ajax['data']['url']), $ajax['data']['url']);	// $path, $filename, $name, $descr, $tags
		$image->process();
		$message[] = $image->message;
		// map photo
		$data = new map($ajax['data']['section'], $ajax['data']['id'], 'photos', $image->id, 1);
		$short = sql('photos', 'preview', $image->id); 
		$html = (new viewShortieMap (new shortie ( 'photos', $image->id, $short, $data->id)))->html;
		$message[] = $data->message; 
		
		break; 
		
	default:
		$message[] = 'No action has been selected for this AJAX call. Data received: ' . json_encode($data, JSON_FORCE_OBJECT);
}

$_SESSION['message'][] = $message; 													// add messages from AJAX switch to session message stack 

// combine the the three elements of the response message into array
$response['message'] = implode( flattenArray ($message), '<br>'); 					// leave only one array layer 
$response['data'] = $data;
$response['html'] = str_replace(array("\r", "\n"), '', $html); 						// remove line breaks, or javascript will choke


echo json_encode($response, JSON_FORCE_OBJECT); 									// output JSON; this will be sent back to browser
?>