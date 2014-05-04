<?php
require "functions.php";

if ((isset($_POST['id'])) && (isset($_POST['name'])) && (isset($_POST['email']))) {
	$id = $_POST['id'];
	$name = $_POST['name'];
	$email = $_POST['email'];
	$json_file = "../requests.json";
	$requests;
	
	$curr_request = array(
	    "id" => $id,
	    "name" => $name,
		"email" => $email,
	);
	
	if (file_exists($json_file)) {
		$requests = json_decode(file_get_contents($json_file), true);
	}
	else {
		$requests = array();
	}
	
	array_push($requests, $curr_request);
	file_put_contents($json_file, json_encode($requests));
	
	$subject = "Requested Movies";
	$message = "<p><b>These movies have been requested:</b></p>".requests_to_html($requests)."<p><a href=\"http://movies.virajchitnis.com\">Movie DB</a> by <a href=\"http://www.virajchitnis.com\">Viraj Chitnis</a></p><p>&nbsp;</p><p><font size=\"2\" color=\"grey\">Do not reply to this email, this email address does not accept incoming mail. To report bugs or for any queries, email me at <a href=\"mailto:chitnisviraj@gmail.com\">chitnisviraj@gmail.com</a>.</font></p>";
	sendmail("\"Viraj Chitnis\" <chitnisviraj@gmail.com>", $subject, $message);
}
else {
	header("Location: ../");
}
?>