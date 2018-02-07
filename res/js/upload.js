// Check for the various File API support.
if (window.File) console.log('window.File supported!');
if (window.FileReader) console.log('window.FileReader supported!'); 
if (window.FileList) console.log('window.FileList supported!'); 

// Define some global variables
var filelist = [];  									// Array containing stack of all upload files
var totalSize = 0; 										// Total size of all upload files
var totalProgress = 0; 									// Current total upload progress
var currentUpload = null; 								// The file that's being uploaded

// Define upload zone
var uploadzone = document.getElementById('uploadzone'); 

// Prevent browser from doing stupid things like displaying the dropped file
function prevent(event) {
    event.stopPropagation();
    event.preventDefault();
}
uploadzone.addEventListener('dragenter', prevent, false);
uploadzone.addEventListener('dragover', prevent, false);
uploadzone.addEventListener('drop', prevent, false);

// Give visual feedback while dragging
function showDrop(event) { uploadzone.className += " dragover"; }
function hideDrop(event) { uploadzone.classList.remove('dragover'); }
uploadzone.addEventListener('dragenter', showDrop, false);
uploadzone.addEventListener('dragleave', hideDrop, false);

// Handle the actual file drop
uploadzone.addEventListener('drop', handleDropEvent, false);

function handleDropEvent(event) {
	 
    // event.dataTransfer.files = list of dropped files
	console.log('Files dropped: ' + event.dataTransfer.files.length); 
	
	// actions for each dropped file
    for (var i = 0; i < event.dataTransfer.files.length; i++) {
		var file = event.dataTransfer.files[i];
		console.log('File name: ' + file.name); 
		console.log('File size: ' + file.size/1000 + ' kB'); 
		console.log('File type: ' + file.type); 
		
		// display dropped files
		var fileicon = document.createElement('DIV'); 
		fileicon.className = 'fileicon'; 
		fileicon.innerHTML = file.name + ', ' + Math.round(file.size/1000) + ' kB'; 
		var progressbar = document.createElement('DIV');
		progressbar.className = 'progressbar';
		fileicon.appendChild(progressbar); 
		
		uploadzone.appendChild(fileicon); 
		
		
		// upload
		var formData = new FormData();
		formData.append('file', file);
		var xhr = new XMLHttpRequest();
		xhr.open('POST', '/system/controller/upload.php');
		xhr.onload = function () {
  			if (xhr.status === 200) {
				console.log('Upload done');
				console.log(formData);
				console.log(progressbar);
			}
			else console.log('Something went terribly wrong...');
  		};

		xhr.send(formData);
		 
		xhr.upload.onprogress = function (event) {
			if (event.lengthComputable) {
				var progress = (event.loaded / event.total * 100 | 0);
				progressbar.style.width = progress +'%';
			}
		};
		/*
		xhr.onload = function () {
  			// just in case we get stuck around 99%
			progressbar.value = progressbar.innerHTML = 100;
		};
		*/
		
    }
}

