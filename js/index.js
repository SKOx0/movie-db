function afterPageLoadDesktop () {
	writeCopyrightYear();
	loadPosters();
}

function afterPageLoadMobile () {
	writeCopyrightYear();
}

function loadPosters () {
	var docElements = document.getElementsByTagName("*");
	for (var i = 0; i < docElements.length; i++) {
		if ((docElements[i].tagName) == "IMG") {
			docElements[i].src = "posters/" + docElements[i].id + ".jpg";
		}
	}
	
	//document.getElementById('name_header').colSpan = "2";
	//document.getElementById('name_button').value = "Poster & Name";
	
	var posters = document.getElementsByClassName('posters');
	for (var i = 0; i < posters.length; i++) {
	  posters[i].style.display = 'block';
	}
	
	removeLoadPostersButton();
}

function afterPageLoad () {
	writeCopyrightYear();
	loadVisiblePosters();
}

$.fn.isOnScreen = function(){
    
    var win = $(window);
    
    var viewport = {
        top : win.scrollTop(),
        left : win.scrollLeft()
    };
    viewport.right = viewport.left + win.width();
    viewport.bottom = viewport.top + win.height();
    
    var bounds = this.offset();
    bounds.right = bounds.left + this.outerWidth();
    bounds.bottom = bounds.top + this.outerHeight();
    
    return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));
    
};

$(window).scroll(function() {
	loadVisiblePosters();
});

function writeCopyrightYear () {
	document.getElementById('copy_year').innerHTML = new Date().getFullYear();
}

function loadVisiblePosters () {
	var movie_boxes = document.getElementsByClassName('movie_box');
	for (var i = 0; i < movie_boxes.length; i++) {
		var curr_box_elements = movie_boxes[i].getElementsByTagName("*");
		
		for (var j = 0; j < curr_box_elements.length; j++) {
			if ((curr_box_elements[i].tagName) == "IMG") {
				if ($("#" + curr_box_elements[i].id).isOnScreen()) {
					curr_box_elements[i].src = "posters/" + curr_box_elements[i].id + ".jpg";
					curr_box_elements[i].style.display = "block";
				}
			}
		}
	}
}

function removeLoadPostersButton () {
	var loadButton = document.getElementById('load_posters');
	loadButton.parentNode.removeChild(loadButton);
}

function submitOrderForm () {
	document.getElementById("orderForm").submit();
}

function showAddOverlay () {
	document.getElementById('overlay_background').style.display = "block";
	document.getElementById('overlay_add').style.display = "block";
	document.body.style.overflow = "hidden";
}

function hideAddOverlay () {
	document.getElementById('overlay_add').style.display = "none";
	document.getElementById('overlay_background').style.display = "none";
	document.body.style.overflow = "auto";
}

function showRestoreOverlay () {
	document.getElementById('overlay_background_bottom').style.display = "block";
	document.getElementById('overlay_restore').style.display = "block";
	document.body.style.overflow = "hidden";
}

function hideRestoreOverlay () {
	document.getElementById('overlay_restore').style.display = "none";
	document.getElementById('overlay_background_bottom').style.display = "none";
	document.body.style.overflow = "auto";
}