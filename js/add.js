function addMovie () {
	var qualities = JSON.parse(document.getElementById('json_holder').innerHTML);
	alert(qualities.join('\n'));
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
	
//	for (var i = 0; i < qualities.length; i++) {
//		var qualOption = document.createElement('option');
//		qualOption.value = qualities[i];
//		qualOption.innerHTML = qualities[i];
//		qualitySelect.appendChild(qualOption);
//	}
	
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