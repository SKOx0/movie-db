var progressTimer = null;

$(function() {
   $("img.posters").lazyload({
       effect : "fadeIn"
   });

});

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

function confirmDelete (movie_name) {
	if (confirm('Are you sure you want to delete ' + movie_name + '?')) {
		return true;
	}
	else {
		return false;
	}
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

function showEditOverlay (id, quality, file_name) {
	document.getElementById('edit_form').elements[0].value = id;
	document.getElementById('edit_form').elements[2].value = file_name;
	
	var form_select = document.getElementById('edit_form').elements[1];
	for (var i = 0; i < form_select.length; i++) {
		if (form_select.options[i].value == quality) {
			form_select.options[i].selected = true;
		}
		else {
			form_select.options[i].selected = false;
		}
	}
	
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

function showEmailOverlay () {
	document.getElementById('overlay_background').style.display = "block";
	document.getElementById('overlay_email').style.display = "block";
	document.body.style.overflow = "hidden";
}

function hideEmailOverlay () {
	document.getElementById('overlay_email').style.display = "none";
	document.getElementById('overlay_background').style.display = "none";
	document.body.style.overflow = "auto";
}

function showProgressOverlay () {
	displayConvertProgress();
	
	document.getElementById('overlay_background').style.display = "block";
	document.getElementById('overlay_progress').style.display = "block";
	document.body.style.overflow = "hidden";
	
	progressTimer = setInterval(displayConvertProgress, 1000);
}

function hideProgressOverlay () {
	document.getElementById('overlay_progress').style.display = "none";
	document.getElementById('overlay_background').style.display = "none";
	document.body.style.overflow = "auto";
	document.getElementById("ffmpeg_progress").innerHTML = "";
	clearInterval(progressTimer);
	document.getElementById("ffmpeg_progress").innerHTML = "Connection failed, reload page.";
}

function displayConvertProgress () {
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.open("GET", "scripts/getlog", true);
	xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			document.getElementById("ffmpeg_progress").innerHTML = "<pre>" + xmlhttp.responseText + "</pre>";
			// Auto scroll to bottom of ffmpeg_progress div
			var ffmpeg_div = document.getElementById("ffmpeg_progress");
			ffmpeg_div.scrollTop = ffmpeg_div.scrollHeight;
		}
	}
	xmlhttp.send();
}

function setCookie(cname,cvalue,exdays) {
	var d = new Date();
	d.setTime(d.getTime()+(exdays*24*60*60*1000));
	var expires = "expires="+d.toGMTString();
	document.cookie = cname + "=" + cvalue + "; " + expires;
}

function getCookie(cname) {
	var name = cname + "=";
	var ca = document.cookie.split(';');
	for(var i=0; i<ca.length; i++) {
		var c = ca[i].trim();
		if (c.indexOf(name)==0) return c.substring(name.length,c.length);
	}
	return "";
}

function checkCookie(cname) {
	var name = getCookie(cname);
	if (name != "") {
		return true;
	}
	else {
		return false;
	}
}

function convertFile (quality, filename, orig) {
	var convert_form = document.getElementById('convert_email_form');
	convert_form.elements[0].value = quality;
	convert_form.elements[1].value = filename;
	convert_form.elements[2].value = orig;
	
	if (checkCookie("email")) {
		convert_form.elements[3].value = getCookie("email");
	}
	
	showEmailOverlay();
}

function startConversion () {
	var convert_form = document.getElementById('convert_email_form');
	var x = convert_form.elements[3].value;
	
	if (x == null || x == ""){
		alert("Email cannot be blank.");
		return false;
	}
	var atpos = x.indexOf("@");
	var dotpos = x.lastIndexOf(".");
	if (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= x.length){
		alert(x + " is not a valid e-mail address.");
		return false;
	}
	
	setCookie("email", x, 30);
}