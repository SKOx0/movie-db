function validateForm(){
	var x = document.forms["movie_form"]["id"].value;
	if (x == null || x == ""){
		alert("IMDB ID cannot be blank.");
		return false;
	}
	x = document.forms["movie_form"]["quality"].value;
	if (x == null || x == ""){
		alert("Please select a quality.");
		return false;
	}
}