// JavaScript Document

window.onload = function() {
	// Listener for TR height toggles 
	var toggleTRHeight = document.getElementsByClassName('toggleTRHeight');
	console.log('Found ' + toggleTRHeight.length + ' table row height toggles'); 
	for ( var i=0; i< toggleTRHeight.length; i++) toggleTRHeight[i].addEventListener('click', toggleTR_height);
	
	// Listener for TR all height toggles 
	var toggleAllTRHeight = document.getElementsByClassName('toggleAllTRHeight');
	console.log('Found ' + toggleAllTRHeight.length + ' table all row height toggles'); 
	for ( var i=0; i< toggleAllTRHeight.length; i++) toggleAllTRHeight[i].addEventListener('click', toggleAllTR_height);

	// Listener for close buttons
	var closebutton = document.getElementsByClassName("close");
	console.log('Found ' + closebutton.length + ' close buttons');
	for ( var i=0; i< closebutton.length; i++) closebutton[i].addEventListener('click', closeModal);
	
	// Listener for edit buttons
	var editbutton = document.getElementsByClassName("editbutton"); 
	console.log('Found ' + editbutton.length + ' edit buttons');
	for ( var i=0; i< editbutton.length; i++) editbutton[i].addEventListener('click', editValue);
		
}