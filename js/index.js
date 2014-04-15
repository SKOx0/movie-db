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

function isElementInViewport (el) {

    //special bonus for those using jQuery
    if (el instanceof jQuery) {
        el = el[0];
    }

    var rect = el.getBoundingClientRect();

    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && /*or $(window).height() */
        rect.right <= (window.innerWidth || document.documentElement.clientWidth) /*or $(window).width() */
    );
}

function writeCopyrightYear () {
	document.getElementById('copy_year').innerHTML = new Date().getFullYear();
}

function loadVisiblePosters () {
	var posters = document.getElementsByClassName('posters');
	for (var i = 0; i < posters.length; i++) {
		if (isElementInViewport(posters[i])) {
			posters[i].src = "posters/" + posters[i].id + ".jpg";
			posters[i].style.display = "block";
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

function showEditOverlay () {
	document.getElementById('overlay_background').style.display = "block";
	document.getElementById('overlay_edit').style.display = "block";
	document.body.style.overflow = "hidden";
	return false;
}

function hideEditOverlay () {
	document.getElementById('overlay_edit').style.display = "none";
	document.getElementById('overlay_background').style.display = "none";
	document.body.style.overflow = "auto";
}

function showRestoreOverlay () {
	document.getElementById('overlay_background').style.display = "block";
	document.getElementById('overlay_restore').style.display = "block";
	document.body.style.overflow = "hidden";
}

function hideRestoreOverlay () {
	document.getElementById('overlay_restore').style.display = "none";
	document.getElementById('overlay_background').style.display = "none";
	document.body.style.overflow = "auto";
}