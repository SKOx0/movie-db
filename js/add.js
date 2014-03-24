function addMovie () {
	var qualities = JSON.parse(document.getElementById('json_holder').innerHTML);
	var parent = document.getElementById('movies_list');
	
	var breakLine = document.createElement('br');
	var space1 = document.createElement('span');
	var space2 = document.createElement('span');
	space1.innerHTML = " ";
	space2.innerHTML = " ";
	
	var idText = document.createElement('input');
	idText.setAttribute('type', 'text');
	idText.setAttribute('name', 'id');
	idText.setAttribute('placeholder', 'IMDB ID');
	
	var qualitySelect = document.createElement('select');
	qualitySelect.setAttribute('name', 'quality');
	
	for (var i = 0; i < qualities.length; i++) {
		var qualOption = document.createElement('option');
		qualOption.value = qualities[i];
		qualOption.innerHTML = qualities[i];
		qualitySelect.appendChild(qualOption);
	}
	
	var filenameText = document.createElement('input');
	filenameText.setAttribute('type', 'text');
	filenameText.setAttribute('name', 'file_name');
	filenameText.setAttribute('placeholder', 'File name');
	
	parent.appendChild(breakLine);
	parent.appendChild(idText);
	parent.appendChild(space1);
	parent.appendChild(qualitySelect);
	parent.appendChild(space2);
	parent.appendChild(filenameText);
}

function createJSON () {
	var dataForm = document.getElementById('movies_list');
	var movies = new Array();
	
	for (var i = 0; i < dataForm.length; i += 3) {
		var currMovie = new Movie();
		currMovie.id = dataForm.elements[i].value;
		currMovie.quality = dataForm.elements[i+1].value;
		currMovie.filename = dataForm.elements[i+2].value;
		movies.push(currMovie);
	}
	
	var jsonified = JSON.stringify(movies);
	alert(jsonified);
	document.getElementById('save_button').elements[0].value = jsonified;
	return false;
}