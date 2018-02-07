// JavaScript Document

registerListener('load', setLazy);
registerListener('load', lazyLoad);
registerListener('scroll', lazyLoad);

registerListener('load', lazyLoadMapped);
registerListener('scroll', lazyLoadMapped);

registerListener('load', lazyLoadMaplings);
registerListener('scroll', lazyLoadMaplings);

var lazy = [];
var lazyMapped = []; 
var lazyMaplings = [];

function setLazy(){
    lazy = document.getElementsByClassName('lazy');
    console.log('Found ' + lazy.length + ' lazy images');
	lazyMapped = document.getElementsByClassName('lazyMapped'); 
	console.log('Found ' + lazyMapped.length + ' lazyMapped elements'); 
	lazyMaplings = document.getElementsByClassName('lazyMaplings'); 
	console.log('Found ' + lazyMaplings.length + ' lazyMaplings elements'); 
} 

function lazyLoad(){
    for(var i=0; i<lazy.length; i++){
		if(isInViewport(lazy[i])){ 
			if (lazy[i].getAttribute('data-src')){
               	lazy[i].src = lazy[i].getAttribute('data-src');
               	lazy[i].removeAttribute('data-src');
			}
        }
    }
    cleanLazy();	
}


function lazyLoadMapped(){
	for (var i=0; i<lazyMapped.length; i++) {
		if (isInViewport(lazyMapped[i])) {
			if (lazyMapped[i].getAttribute('item_id')) {
				var action = {}; 
				action.spot = lazyMapped[i];
				action.data = {
					section: lazyMapped[i].getAttribute('item_section'), 
					id: lazyMapped[i].getAttribute('item_id'), 
					mapped_section: lazyMapped[i].getAttribute('mapped_section')
				};
				action.action = 'getMapped';
				ajax(action);
				lazyMapped[i].removeAttribute('item_id'); 
			}
	 	}
	}
	cleanLazyMapped(); 
}

function lazyLoadMaplings(){
	for (var i=0; i<lazyMaplings.length; i++) {
		if (isInViewport(lazyMaplings[i])) {
			if (lazyMaplings[i].getAttribute('item_id')) {
				var action = {}; 
				action.spot = lazyMaplings[i];
				action.data = {
					section: lazyMaplings[i].getAttribute('item_section'), 
					id: lazyMaplings[i].getAttribute('item_id'),
					mapling_section: lazyMaplings[i].getAttribute('mapling_section') 
				};
				action.action = 'getMaplings';
				ajax(action);
				lazyMaplings[i].removeAttribute('item_id'); 
			}
	 	}
	}
	cleanLazyMaplings(); 
}

function cleanLazy(){
    lazy = Array.prototype.filter.call(lazy, function(l){ return l.getAttribute('data-src');});
}

function cleanLazyMapped(){
    lazyMapped = Array.prototype.filter.call(lazyMapped, function(l){ return l.getAttribute('item_id');});
}

function cleanLazyMaplings(){
    lazyMaplings = Array.prototype.filter.call(lazyMaplings, function(l){ return l.getAttribute('item_id');});
}

function isInViewport(el){
    var rect = el.getBoundingClientRect();
    
    return (
        rect.bottom >= 0 && 
        rect.right >= 0 && 
        rect.top <= (window.innerHeight || document.documentElement.clientHeight) && 
        rect.left <= (window.innerWidth || document.documentElement.clientWidth)
     );
}

function registerListener(event, func) {
    if (window.addEventListener) {
        window.addEventListener(event, func)
    } else {
        window.attachEvent('on' + event, func)
    }
}