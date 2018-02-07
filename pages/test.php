<?php 
	session_start();
	$action = 'update';
	$id=$_GET['id'];
	$section = $_GET['section'];
	
	require ($_SERVER['DOCUMENT_ROOT'] . '/system/documenthead.php');  // includes <body> tag as well as jquery, sorttable.js, datepicker.js
	
	

?>

<script>

function Action(){
	this.comment = 'this is the original object'; 
	
}

action = new Action(); 

function test() {
	console.log('Testing scope - test:'); 
	console.log(action.comment); 
}


function inbetween(action) {
	console.log('Testing scope - inbetween:'); 
	console.log(action.comment); 
	test(); 
}

test(); 
// test(action); 

// inbetween({comment: 'Setting the object to something else ...'});



</script>
</body>
</html>