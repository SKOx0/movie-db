function afterPageLoadDesktop () {
	writeCopyrightYear();
	loadPosters();
}

function afterPageLoadMobile () {
	writeCopyrightYear();
}

function writeCopyrightYear () {
	document.getElementById('copy_year').innerHTML = new Date().getFullYear();
}

function loadPosters () {
	var docElements = document.getElementsByTagName("*");
	for (var i = 0; i < docElements.length; i++) {
		if ((docElements[i].tagName) == "IMG") {
			docElements[i].src = "posters/" + docElements[i].id + ".jpg";
		}
	}
	removeLoadPostersButton();
}

function removeLoadPostersButton () {
	var loadButton = document.getElementById('load_posters');
	loadButton.parentNode.removeChild(loadButton);
}