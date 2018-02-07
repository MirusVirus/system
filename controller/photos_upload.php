<?php
session_start();
require_once ('../autoload.php'); 

if ( $_FILES['photo1']['error'] == 4 && 
	$_FILES['photo2']['error'] == 4 && 
	$_FILES['photo3']['error'] == 4 &&
	!$_POST['url1'] &&
	!$_POST['url2'] &&
	!$_POST['url3']	) {
	$_SESSION['message'][] = '<span class="uh">No files had been selected for upload, or no URL was specified.</span>';
}

// Photo 1
if ( $_FILES['photo1']['error'] !== 4 ) { 		// whether a file had been selected for upload
	$image = new image($_FILES['photo1']['tmp_name'], $_FILES['photo1']['name'], $_POST['name1'], $_POST['descr1']);	// $path, $filename, $name, $descr, $tags
	$image->process();
	$_SESSION['message'][] = $image->message; 
	$image = NULL; // free memory
}
if ( $_POST['url1']) { 							// whether a url had been indicated
	copy($_POST['url1'], $_SERVER['DOCUMENT_ROOT'] . $tmpimg . 'tmp1');
	$image = new image($_SERVER['DOCUMENT_ROOT'].$tmpimg.'tmp1', $_POST['url1'], $_POST['name1'], $_POST['descr1']);	// $path, $filename, $name, $descr, $tags
	$image->process();
	$_SESSION['message'][] = $image->message; 
	$image = NULL; 								// free memory
}
// Photo 2
if ( $_FILES['photo2']['error'] !== 4 ) { 		// whether a file had been selected for upload
	$image = new image($_FILES['photo2']['tmp_name'], $_FILES['photo2']['name'], $_POST['name2'], $_POST['descr2']);
	$image->process();
	$_SESSION['message'][] = $image->message; 
	$image = NULL; 								// free memory
}
if ( $_POST['url2']) { 							// whether a url had been indicated
	copy($_POST['url2'], $_SERVER['DOCUMENT_ROOT'] . $tmpimg . 'tmp2');
	$image = new image($_SERVER['DOCUMENT_ROOT'] . $tmpimg . 'tmp2', $_POST['url2'], $_POST['name2'], $_POST['descr2']);
	$image->process();
	$_SESSION['message'][] = $image->message; 
	$image = NULL; 								// free memory
}
// Photo 3
if ( $_FILES['photo3']['error'] !== 4 ) { 		// whether a file had been selected for upload
	$image = new image($_FILES['photo3']['tmp_name'], $_FILES['photo3']['name'], $_POST['name3'], $_POST['descr3']);
	$image->process();
	$_SESSION['message'][] = $image->message; 
	$image = NULL; 								// free memory
}
if ( $_POST['url3']) { 							// whether a url had been indicated
	copy($_POST['url3'], $_SERVER['DOCUMENT_ROOT'] . $tmpimg . 'tmp3');
	$image = new image($_SERVER['DOCUMENT_ROOT'] . $tmpimg . 'tmp3', $_POST['url3'], $_POST['name3'], $_POST['descr3']);
	$image->process();
	$_SESSION['message'][] = $image->message; 
	$image = NULL; 								// free memory
}

header("Location: /system/pages/sectionview.php?section=photos", true, 301);
exit;
?>