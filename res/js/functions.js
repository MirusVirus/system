// JavaScript Document

// Action - most things AJAX happen based on an Action object. 
// Data is step by step collected into the object which is then sent to server (ajax.php).
// Based on Action data, ajax.php knows what to do. 
function Action(spot, action, data) { 											// creates an action object. Button onclick feeds in initial data
	this.data = data; 															// whatever data is needed
	this.action = action; 														// determines how to proceed
	this.spot = spot; 															// where this action was originated

	console.log('Created action object:');
	console.log(JSON.stringify(this, null, 4))
}


// Need this separate exec function because Action object is not within scope
// when functions are called from inside Action object
function exec() {
	
	if ( in_array(action.action, dialog_actions)) { 							// $dialog_actions is defined in config.php and output to js in documenthead.php
	
		// highlight if active table row
		if (action.spot.parentNode.parentNode.parentNode.nodeName == 'TR') highlight(action.spot.parentNode.parentNode.parentNode); 	
		if (action.spot.parentNode.parentNode.parentNode.parentNode.nodeName == 'TR') highlight(action.spot.parentNode.parentNode.parentNode.parentNode); 
	
		console.log('For this action, a dialogue is required.');
		showDialog(); 
		insertData(); 															// inserts appriate strings into all <span class="jsinsert">
		if ( in_array(action.action, dynamic_dialogs)) { 						// $dynamic_dialog is defined in config.php and output to js in documenthead.php
			console.log('For this action, dynamic content is needed in dialogue.');
			requestInput(); 
		}
	}
	
	else ajax(action); 															// No further action needed - AJAX straight away
	
}


function highlight(element) { 													// highlight color should always be same, hence a separate function
	element.style.background = 'rgba(255,255,0,.3)'; 
}


function showDialog() { // name of action is id of dialog
	action.dialog = document.getElementById(action.action);
	action.dialog.style.display = "block";
}


function insertData() {															// insert data into dialogue; loop through data object
	console.log('Inserting data into dialogue:');
	console.log(action.data);
	for ( var key in action.data ) { 											// loop through action data
		var insert = document.getElementsByClassName('jsinsert ' + key); 		// get a list of corresponding nodes
		for (var i = 0; i<insert.length; i++) { 								// loop through them ...
			insert[i].innerHTML = action.data[key]; 							// ... and insert
		}	
	}
}


// in case input from db is needed to fill the dialogue: here it happens
// **** can this be done with Action object also??? ****
function requestInput() {														
	var request = {}; 
	request.action = action.action+'_input'; 
	request.data = {}; 
	switch (action.action) {
		case 'map1': request.data.mapped_section = action.data.mapped_section; break; 
		case 'map2': request.data.mapling_section = action.data.mapling_section; break; 
	}
	ajax(request); 
}
	

function fillDialog(response) {
	
	action.dialog_dyn = document.getElementById(action.action + '_dyn'); 			// the part of dialog_body that's to be filled dynamically
	action.dialog_dyn.innerHTML = ''; 												// Clean dynamic part of modals, e.g. remove loader
	console.log('Reset content of ' + action.dialog_dyn);
	
	console.log('filling dialogue - action was: ' + action.action);
	
	switch (action.action){
		
		case 'copy':  																// creates an item in another section and copies columns with matching name
			console.log(sections);
			var recreateLinks = document.getElementById('recreateLinks');
			var linkCopy 	  = document.getElementById('linkCopy');
			for ( var i=0; i<sections.length; i++ ) { 								// loop through objects containing the maplings of this section
				(function(i) { 														// need to wrap in anonymous function, otherwise next loop will overwrite previous property
					var btn = document.createElement('button'); 					// create button element
					var copy_section = sections[i];
					btn.innerHTML = copy_section; 									// insert mapling name
					action.dialog_dyn.appendChild(btn); 							// append button to modal window
					btn.onclick = function() {
						action.data.copy_section  = copy_section; 					// add the missing piece to action
						action.data.recreateLinks = (recreateLinks.checked) ? true : false;
						action.data.linkCopy      = (linkCopy.checked)      ? true : false;
						ajax(action);
						closeModal();
					}
				})(i);
			}
		break; 
		
		case 'map1':  // this is the mapling and will be mapped to another section
			console.log(response);
			for ( var key in response.data.items  ) { 								// loop through objects containing the maplings of this section
				(function(key) { 													// need to wrap in anonymous function, else next loop will overwrite previous property
					var btn = document.createElement('button'); 					// create button element
					btn.innerHTML = response.data.items[key].name; 					// insert mapling name
					action.dialog_dyn.appendChild(btn); 							// append button to modal window
					btn.onclick = function() {
						action.data.mapped_id = response.data.items[key].id; 		// add the missing piece to action
						ajax(action);
						// closeModal();
					}
				})(key);
			}
		break; 
		
		case 'map2':  																// a mapling from another section will be mapped to this
		
		// prepare buttons to filter by tag if mapling section is tagged
		if (response.data.tags) { 
			console.log('Tags available:');
			console.log(response.data.tags);
			var filters = document.createElement('p'); 								// create paragraph to house all filter buttons
			action.dialog_dyn.appendChild(filters);
			filters.innerHTML = 'Filter by tags: ';
			var filterbtn = document.createElement('button');						// Button to clear filters 
			filterbtn.innerHTML = 'all';
			filterbtn.style.fontWeight = 'bold'; 
			filters.appendChild(filterbtn); 
			filterbtn.onclick = function() {filter(this.innerHTML);}; 				
			for (var key in response.data.tags) {									// More filter buttons for each tag
				var filterbtn = document.createElement('button');
				filterbtn.innerHTML = response.data.tags[key];
				filters.appendChild(filterbtn); 
				filterbtn.onclick = function() {filter(this.innerHTML);};
			}
						
		}
		
		// prepare div with maplings
		var sel = document.createElement('div'); 

		for ( var key in response.data.items ) {				 					// loop through objects containing the maplings of this section
			(function(key) { 														// need to wrap in anonymous function, else next loop will overwrite previous property
				if ( response.data.section == "photos" ) {
					// console.log(sectiondata.maplings[mapling_section][key]);
					var btn = document.createElement('img'); 						// create image element, which is a button
					btn.src = uploaddir + response.data.items[key].preview;   		// assign img source
				}
				else {
					var btn = document.createElement('button'); 					// create button element
					btn.innerHTML = response.data.items[key].name; 					// get mapling name
				}
				if (response.data.tags) btn.className = response.data.items[key].tags; // tags become class names for filtering
				action.dialog_dyn.appendChild(btn); 								// append image element to modal window
				btn.onclick = function() {
					action.data.mapling_id = response.data.items[key].id; 			// add the missing piece to action
					ajax(action);
					// closeModal();
				}
			})(key);
		}
		break;
			
		case 'search':  															// search in all tables if used on index page or search single table if used on section page
		console.log('Dialogue is complete already');
		break;
	}																				// end switch
}

function toggleTR_height() {
	this.classList.toggle('rotate90'); 
	var TR = this.parentNode.parentNode; 
	var tr_height = TR.querySelectorAll('.tr_height'); 
	console.log(tr_height);
	for (var i=0; i<tr_height.length; i++) {
    	tr_height[i].classList.toggle('max');
	}
}

function toggleAllTR_height() {
	var table = this.parentNode.parentNode.parentNode.parentNode; 
	// expand all
	if (!this.classList.contains('rotate90')) { 									// generally minimized, need to maximize all
		this.classList.add('rotate90'); 
		// get all tr_height divs and expand them
		var tr_height = table.querySelectorAll('.tr_height'); 
		for (var i=0; i<tr_height.length; i++) {
			tr_height[i].classList.add('max');
		}
		// get all toggle arrows and rotate them
		var toggleTRHeight = table.querySelectorAll('.toggleTRHeight'); 
		for (var i=0; i<toggleTRHeight.length; i++) {
			toggleTRHeight[i].classList.add('rotate90');
		}
	}
	else { 																			// generally maximized, need to minimize all
		this.classList.remove('rotate90'); 
		// get all tr_height divs and expand them
		var tr_height = table.querySelectorAll('.tr_height'); 
		for (var i=0; i<tr_height.length; i++) {
			tr_height[i].classList.remove('max');
		}
		// get all toggle arrows and rotate them
		var toggleTRHeight = table.querySelectorAll('.toggleTRHeight'); 
		for (var i=0; i<toggleTRHeight.length; i++) {
			toggleTRHeight[i].classList.remove('rotate90');
		}
	}
}

function filter(tag) {
	// alert('Filter by ' + tag); 
	var show = (tag == 'all') ? document.querySelectorAll('.modal img') : document.querySelectorAll('.modal img.' + tag); 
	var noshow = document.querySelectorAll('.modal img:not(.' + tag +')');
	for (var i=0; i<noshow.length; i++) {
		noshow[i].style.display='none'; 
	}
	for (var i=0; i<show.length; i++) {
		show[i].style.display='inline-block'; 
	}
}

function closeModal() { 															// hides all modal windows 
	var modal = document.getElementsByClassName("modal"); 							// get a list of modal windows
	for (var i=0; i<modal.length; i++) { // hide modal
		modal[i].style.display = "none"; 
		console.log('Closed all modals.');
	}
	
	var tablerows = document.getElementsByTagName("tr");
	for (var i=0; i<tablerows.length; i++) {
		tablerows[i].style.background = "none"; 									// unhighlight tablerow
	}
}

function resetModal() {
	var resetcont = document.getElementsByClassName("resetcont"); 					// get a list of containers with dynamic content that need to be emptied
	for (var i=0; i<resetcont.length; i++) {
		resetcont[i].innerHTML = ''; 												// reset content of all elements with class "resetcont"
		console.log('Reset content of ' + resetcont.length + ' modals.');
	}
}
	

window.onclick = function(event) { 													// When user clicks anywhere outside of the modal, close it
    if (event.target.classList.contains('modal')) {
        closeModal();
		// modal.style.display = "none";
		// tablerow.style.background = "none";
    }
}

function showMsg () { 																// checks whether a message is set and displays message box
	var msgbox = document.getElementById("msgbox"); 
	if ( message !='' ) {
    	msgbox.style.display = "block";
		msgbox.innerHTML = message;
	}
	msgbox.onclick = function() {
		msgbox.style.display = "none";
		resetMsg();
	}
}

function resetMsg() {
	var action = {action:'resetMsg'};
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
    	if (this.readyState == 4 && this.status == 200) {
			console.log("Emptied message box.");
			console.log(this.responseText);
			message = ''; 
			showMsg(); // checks whether a message is set and displays message box
   		 }
	};
	xhttp.open("POST", "/system/controller/ajax.php", true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.send(JSON.stringify(action));
}

function ajax(action) { 														// all AJAX calls use this function. ajax.php will find the right action. 
	document.getElementById('ajaxloader').style.opacity = 1;
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			console.log("AJAX " + action.action + " completed, AJAX data were:");
			console.log(action);
			document.getElementById('ajaxloader').style.opacity = 0;
			console.log('AJAX response:');
			console.log(this.responseText); 
			var response = JSON.parse(this.responseText); 						// create JS object from JSON string
			console.log(JSON.stringify(response, null, 4));
			
			// get message part of response and add to message in box. If stack not empty, add <br>
			if (response.message) message += (message !='' ? '<br>' : '' ) + response.message; 		
			showMsg(); 															// checks whether a message is set and displays message box
			
			// do more things, depending on action:
			switch(action.action) {
				case 'delete': case 'unmap': case 'unprint':
					remove(action, response); 
					break; 
				case 'copy_input': case 'map1_input': case 'map2_input': case 'search_input':
					fillDialog(response); 
					break; 
				case 'filter_input':
					createDropdown(response); 
					break; 
				case 'search': 
					insert(action, response); 
					break; 
				case 'map1':
					insert(action, response); 
					if (page == 'sectionview') registerMapped(action, response);		// adds mapling information to object items
					if ( filters.length > 0 ) updateFilters();				// applies filter to table rows according to newly added maps
					break; 
				case 'urlphoto':
					console.log(action.spot); 
					console.log(action.spot.previousSibling); 
					console.log(action.spot.previousSibling.parentNode); 
					insert(action, response); 
					break;
				case 'map2': // case 'urlphoto': 
					insert(action, response); 
					break; 
				case 'tag': case 'untag': 
					insert(action, response); remove(action, response); 
					break; 
				case 'getMapped':
					action.spot.innerHTML = response.html; 					// replaces the innerHTML of the appropriate container
					if (page == 'sectionview') registerMapped(action, response);		// adds mapling information to object items
					if ( filters.length > 0 ) updateFilters();				// applies filter to table rows according to newly added maps
					break; 
				case 'getMaplings': 
					action.spot.innerHTML = response.html; 					// replaces the innerHTML of the appropriate container
					break; 		
				case 'confirm':
					var datainsert = action.spot.parentNode.parentNode.parentNode.querySelector('td[name="status"]').childNodes[0];
					datainsert.innerHTML = 'confirmed'; 
					highlight(datainsert); 
					break;
				case 'graph1': 
					showGraph(response); 
					break; 
				case 'print': 
					action.spot.style.background = 'skyblue'; 
					break; 
				// all actions that only require reload
				case 'lang': case 'timesheet': case 'timesheetCopy': case 'timesheetDelete':
					location.reload(true); 									// reload, force not to use cache
					break; 
			}
		}
	};
	xhttp.open("POST", "/system/controller/ajax.php", true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.send(JSON.stringify(action)); 
}


// This is called onchange of 1st filter dropdown (select section of filtering item) 
function FilterByItem(spot) { 														// create a filter object
	this.section = spot.value; 	
	this.spot = spot; 															// where this action was originated
	
	console.log('Creating a new filter object:');
	console.log(JSON.stringify(this, null, 4))
	
	// We need a 2nd dropdown for selecting a filtering item within the selected section ...
	if (typeof mapped !== 'undefined') mapped.innerHTML = ''; 					// ... if a dropdown is there already, empty it
	
	// Put a loader first
	mapped_loader = document.createElement('IMG'); 
	mapped_loader.src = '/system/img/loader.svg';
	mapped_loader.id = 'mapped_loader';
	spot.parentNode.insertBefore(mapped_loader, spot.nextSibling);
	
	// Request input: all items that any item of this section is mapped to
	var request = {}; 
	request.spot = this;
	request.action = 'filter_input';											// this tells ajax.php knows what to do 
	request.data = {}; 
	request.data.mapped_section = this.section;
	ajax(request); 																// function ajax(action) will direct to the next steps (function createDropdown)
	
}


// create dropdown for filters
function createDropdown(response) {

	var mapped = document.getElementById('mapped_dropdown') || document.createElement('SELECT'); 			// create dropdown if not there already
	mapped.innerHTML = ''; 														// empty any old data
	
	mapped.id = 'mapped_dropdown';
	mapped_loader.parentNode.insertBefore(mapped, mapped_loader.nextSibling);	// insert dropdown after loader
	mapped_loader.style.display = 'none';										// hide loader
	
	// Default options: select, all, none
	pls_select = document.createElement("OPTION");								
	pls_select.innerHTML = 'Please select';
	pls_select.disabled = 'disabled';
	pls_select.selected = 'selected';
	mapped.appendChild(pls_select);
	
	option_any = document.createElement("OPTION");
	option_any.value = 'any';
	option_any.innerHTML = '- any of them';
	mapped.appendChild(option_any);
	
	option_none = document.createElement("OPTION");
	option_none.value = 'none';
	option_none.innerHTML = '- none of them';
	mapped.appendChild(option_none);
	
	// Specific options based in item lists
	var option = []; 
	for ( var key in response['data']['items']) { 								// loop through object of mapped items and create options	
		option[key] = document.createElement("OPTION");
		option[key].value = response['data']['items'][key]['id'];
		option[key].innerHTML = response['data']['items'][key]['name'];
		mapped.appendChild(option[key]);
	}
	
	mapped.onchange = function() {
		// add filter to var filters
		filter.id = this.value;
		console.log('Filter section: ' + filter.section);
		console.log('Filter id: ' + this.value); 
		filters.push({section: filter.section, id: filter.id});
		
		// create filter button												
		var filterdiv = document.getElementById('filters');
		var filterbtn = document.createElement('DIV'); 
		filterbtn.className = 'shortie filter';
		filterbtn.id = filter.section+filter.id;
		var label = document.createElement('A');
		label.innerHTML = type(filter.section) + '<span style="font-weight:600"> ' + this.options[this.selectedIndex].text + '</span>';
		label.href= '/system/pages/itemview.php?section=' + filter.section + '&id=' + filter.id;
		label.target = '_blank';
		filterbtn.appendChild(label);
		var remove = document.createElement('SPAN');
		remove.innerHTML = '&ensp; &times;';
		remove.className = 'remove';
		remove.setAttribute('section', filter.section); 
		remove.setAttribute('id', filter.id);
		
		// click function for unfiltering
		remove.onclick = function() {
			// remove button
			this.parentNode.style.display = 'none';
			
			// delete element in filters array
			section = this.getAttribute('section');
			id = this.getAttribute('id'), 
			console.log('Unfilter: ' + type(section) + ' ' + id);
			for ( var i = 0; i < filters.length; i++ ) {
				if ( filters[i]['section'] == section && filters[i]['id'] == id) filters.splice(i, 1);
			}
			
			// apply filters
			updateFilters(); 
		};
		
		// append
		filterbtn.appendChild(remove);
		filterdiv.appendChild(filterbtn);
		
		// apply filters
		updateFilters(); 
		
		// reset dropdown
		pls_select.selected = 'selected';
		
	}
}

function updateFilters() {
	// update "show" values in item array
	
	for (var key in items ) {													// iterate through items
		if ( filters.length > 0 ) {												// if there are filters ...
			items[key]['show'] = 'false';										// ... first unshow them by default
			for (var j = 0; j < filters.length; j++ ) {							// iterate through filters
				
				switch ( filters[j]['id']) {
					case 'any': // filtering for any mapped item from section - filter section must be in mappedsections
					for (var i = 0; i < items[key]['mapped'].length; i++) {		// iterate through maps
						if ( filters[j]['section'] == items[key]['mapped'][i]['section'] ) {
							items[key]['show'] = 'true';
							break; 												// no need to iterate further
						}
					}
					break; 
					
					case 'none': // filtering for no mapped item from section - filter section must not be in mappedsections
					var noproject = true; 
					for (var i = 0; i < items[key]['mapped'].length; i++) {		// iterate through maps
						if ( filters[j]['section'] == items[key]['mapped'][i]['section'] ) {
							noproject = false; 
							break;
						}
					}
					if ( noproject == true ) items[key]['show'] = 'true';
					break;
					
					default: // filtering for specific item - need to loop through maps and compare
					for (var i = 0; i < items[key]['mapped'].length; i++) {		// iterate through maps
						if ( filters[j]['section'] == items[key]['mapped'][i]['section'] &&	filters[j]['id'] == items[key]['mapped'][i]['id']) {
							items[key]['show'] = 'true';						// if filter match, set to true
						}
					}
				}
			}
		}
		else items[key]['show'] = 'true';
	}
	applyFilters();	
}


function applyFilters() {														// applies filters to table rows
	
	var rows = document.getElementsByClassName('itemrow'); 
	for ( var i = 0; i < rows.length; i++ ) {
		if ( filters ) {														// if filters are set ...
			if ( filters.length > 0 ) {											// and if there are actually filters ...
				var id = rows[i].getAttribute('name');
				if ( items[id]['show'] !== 'true' ) rows[i].style.display = 'none';
				else rows[i].style.display = 'table-row'; 		
			}
			else rows[i].style.display = 'table-row';  
			
			// let lazyLoad recalculate
			lazyLoad();
			lazyLoadMaplings(); 
			lazyLoadMapped();
		}
	}
}

function unfilterAll() {
	var filterbtns = document.getElementsByClassName('filter');
	for ( var i = 0; i < filterbtns.length; i++ ) {
		filterbtns[i].style.display = 'none'; 
	}
	filters = [];
	updateFilters(); 
}


function insert(action, response) { 											// appends elements created from html string to the appropriate container
	console.log('Inserting new elements ... action data:');
	console.log(JSON.stringify(action, null, 4));
	var new_el = document.createElement('template'); 							// create template element that can be filled with anything
	new_el.innerHTML = response.html; 											// fill template with php-generated html string
	var ins; 																	// the node to which will be inserted
	
	// define node to insert into, depending on action:																
	switch(action.action) { 
		case 'tag':					ins = action.spot.parentNode.parentNode.firstChild; break; 
		case 'unmap': 				ins = action.spot.parentNode; break;
		case 'untag': 				ins = action.spot.parentNode.parentNode.parentNode.children[1]; break; 
		case 'map1': case 'map2':  	ins = action.spot.previousSibling; break;  
		case 'search': 				ins = action.dialog_dyn; break;
		case 'addFilter': 			ins = action.spot.parentNode.nextSibling; break;
		case 'urlphoto': 			ins = action.spot.previousSibling.parentNode; break;  
		default: console.log('No container specified for inserting response.html!'); 
	}
	ins.appendChild(new_el.content.cloneNode(true), action.spot); 				// insert and activate template
}


function remove(action) { 														// removes elements that are obsolete after AJAX
	console.log('Removing obsolete elements ...');
	
	if (action.action == 'unfilterAll') {
		var filterbuttons = document.getElementsByClassName('filterbutton'); 
		for ( var i = 0; i < filterbuttons.length; i++) {
			filterbuttons[i].style.display = 'none'; 
		}
	}
	else {
		var rem; 																	// the node to be removed
		switch(action.action) { 
			case 'delete': 					rem = action.spot.parentNode.parentNode.parentNode; break;
			case 'tag': 					rem = action.spot; break; 
			case 'unfilter': case 'unmap': case 'unprint':	
											rem = action.spot.parentNode; break;
			case 'untag': 					rem = action.spot.parentNode; break; 
			default: console.log('No node specified for removal!'); 
		}
		rem.style.display = 'none'; 
	}
	
	// if (action.spot.parentNode.firstChild.outerHTML.includes("comment")) {	action.spot.parentNode.firstChild.style.display = "none"; }
}


function registerMapped(action, response) {										// adds mapling information to object items
	console.log('Registering mapped ...');
	if ( !items[action.data.id]['mapped'].length) items[action.data.id]['mapped'] = [];
	for (var key in response.data.items ) {
		items[action.data.id]['mapped'].push(response.data.items[key]);			// add mapping info ti item list
	}
}


function showGraph(response) {
	
	// convert json objects to arrays, 
	// that's necessary because ajax response enforces object to be consistent across ajax calls
	var nodes = Object.values(response.data.nodes);
	var edges = Object.values(response.data.edges);
	for (var i = 0; i < edges.length; i++) {
		edges[i] = Object.values(edges[i]); 
	}
	
	var graphdata = { 'nodes': nodes, 'edges': edges }; 						// combine both arrays into object again
	
	console.log('graphdata:'); 
	console.log(JSON.stringify(graphdata, null, 4)); 
	
	jQuery(function(){
		var graph = new Springy.Graph();
		graph.loadJSON(graphdata);

		var springy = jQuery('#graph_canvas').springy({
			graph: graph
		});
	});

}

function editValue() {															// update a single value 
	
	// collect data
	var cell = this.parentNode; 
	var innercell = cell.firstChild;									
	var row = cell.parentNode;
	var table = row.parentNode.parentNode;
	var section = table.getAttribute('section'); 
	var item_id = row.getAttribute('name');										// 
	var column = cell.getAttribute('column');
	var type = cell.getAttribute('type');
	var oldVal; 
	if (type == 'website') oldVal = cell.getAttribute('value')					// get original url, because cell contains functionable link 
	else oldVal = innercell.innerHTML;  
	var editbutton = this;
	var editbutton2 = cell.querySelectorAll('.editbutton2');					// Save and cancel button
	var savebtn = cell.querySelector('.save');
	var cancelbtn = cell.querySelector('.cancel');	
	
	var rect = innercell.getBoundingClientRect();
	// console.log(rect); 
	
	// prepare edit mode
	editbutton.style.display = 'none'; 											// hide edit button
	for ( var i=0; i < editbutton2.length; i++ ) { 								// show save & cancel button
		editbutton2[i].style.display = 'inline-block'; 
	}
	console.log('Cancelbtn: ' +cancelbtn); 	
	console.log('Savebtn: ' +savebtn); 		
	innercell.innerHTML = '';													// empty cell
	
	
	// toggle to input mode, depending on type
	switch (type) {
		case 'cal_week': case 'dropdown': case 'month': 
			var input = document.createElement('SELECT'); 						// create input element	
			innercell.appendChild(input);
			
			console.log('Dropdown options: '); 	
			console.log(typedefs[column]['dropdown']); 							// drowdown options defined in config.php
			
			var option = []; 
			for ( var i=0; i < typedefs[column]['dropdown'].length; i++ ) { 	// loop through options and append them to dropdown						
				option[i] = document.createElement("OPTION");
				option[i].value = typedefs[column]['dropdown'][i];
				option[i].innerHTML = typedefs[column]['dropdown'][i];
				if ( oldVal == typedefs[column]['dropdown'][i] ) option[i].selected = 'selected'; 
				input.appendChild(option[i]);
			}
			input.focus();
		break;
		
		case 'yesno':
			var input = document.createElement('INPUT'); 						// create input element	
			input.type = "checkbox";
			innercell.appendChild(input);
			
			input.focus();
		break;

		default:
			var input = document.createElement('textarea'); 					// create input element
			input.type = 'text';
			input.style.width = rect.width-2 +'px';								// make input element as wide as parent cell	
			input.style.height = rect.height+16 +'px';							// make input element as wide as parent cell														
			input.value = oldVal; 												// insert old value
			innercell.appendChild(input);
			input.focus();
			input.select();

	}
	
	highlight(cell); 
	console.log('Now in edit mode: ' + section  + ' ' + item_id + ', column ' + column);
	
	
	// on save button click, toggle back to normal and update value in db
	
	savebtn.onclick = function(){
		newVal = input.value;													// get the updated value from input field	
		for ( var i=0; i < editbutton2.length; i++ ) { 							// hide save & cancel button
			editbutton2[i].style.display = 'none'; 
		}				
		editbutton.style.display = 'block'; 									// bring edit button back
		input.style.display = 'none';											// get rid of input element
		switch (type) {
			case 'website': 
			var prettyURL = cleanURL(newVal);					 				// display new value without(http(s)://
			innercell.innerHTML = '<a href="' + newVal + '" target="_blank">' + prettyURL + '<a>'; 
			cell.setAttribute('value', newVal); 
			break; 
			default: innercell.innerHTML = newVal;								// write new value in cell
		}
		cell.style.background = 'none'; 										// unhighlight
		
		if (oldVal != newVal) {													// if no change is made, no need to update db
			var action = { action: 'updateValue' };								// create action object
			action.data = {};
			action.data.section = section;
			action.data.id = item_id; 
			action.data.column = column; 
			action.data.newVal = newVal;
			ajax(action); 														// execute update via AJAX
		}
	}
	
	// on cancel button click, toggle back to normal and restore old value
	
	cancelbtn.onclick = function(){
		for ( var i=0; i < editbutton2.length; i++ ) { 							// hide save & cancel button
			editbutton2[i].style.display = 'none'; 
		}				
		editbutton.style.display = 'block'; 									// bring edit button back
		input.style.display = 'none';											// get rid of input element
		switch (type) {
			case 'website': 
			var prettyURL = cleanURL(oldVal);					 				// display new value without(http(s)://
			innercell.innerHTML = '<a href="' + oldVal + '" target="_blank">' + prettyURL + '<a>'; 
			cell.setAttribute('value', oldVal); 
			break; 
			default: innercell.innerHTML = oldVal;								
		}								// write new value in cell
		cell.style.background = 'none'; 										// unhighlight
	}
	
	
}

function editCheckbox(spot) {													// Will be triggered checkbox onchange 
	var cell = spot.parentNode.parentNode;
	var row = spot.parentNode.parentNode.parentNode;
	var column = cell.getAttribute('column');
	var item_id = row.getAttribute('name');									// 
	
	var action = { action: 'updateValue' };										// create action object and update db
		action.data = {};
		action.data.section = section;
		action.data.id = item_id; 
		action.data.column = column; 
		action.data.newVal = spot.checked;
		ajax(action); 	
}


// simulate "in_array" php command 
function in_array(needle, haystack) {
    for(var i in haystack) {
        if(haystack[i] == needle) return true;
    }
    return false;
}

// generate type from section
function type(section) {
	return section.slice(0, -1);
}

// find all siblings of an element
function getSiblings (elem) {
    var siblings = [];
    var sibling = elem.parentNode.firstChild;
    var skipMe = elem;
    for ( ; sibling; sibling = sibling.nextSibling ) 
       if ( sibling.nodeType == 1 && sibling != elem )
          siblings.push( sibling );
    return siblings;
}

// return the closest ancestor that is of tag type x
function getAncestorByClassName (el, cls) {
    while ((el = el.parentElement) && !el.classList.contains(cls));
    return el;
}

// return the closest ancestor that has a class name
function getAncestorByTagName(el, tagname) {
	if (el === null || tagname === '') return;
	var parent  = el.parentNode;
	tagname = tagname.toUpperCase();

	while (parent.tagName !== "HTML") {
		if (parent.tagName === tagname) return parent;
		parent = parent.parentNode;
	}

	return parent;
}

// convert a url into a pretty and functionable link


function cleanURL(url) {
	if(url.match(/https:\/\//)) url = url.substring(8);
    if(url.match(/http:\/\//)) url = url.substring(7);
    if(url.match(/^www\./)) url = url.substring(4);
    return url;
}