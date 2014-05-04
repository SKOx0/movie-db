<?php
function sendmail($to, $subject, $message) {
	$headers = "From: \"Movie DB\" <movies@virajchitnis.com>"."\r\n".
		"MIME-Version: 1.0"."\r\n".
		"Content-Type: text/html";

	mail($to, $subject, $message, $headers);
}

function queue_to_html($queue) {
	$html_text = "";
	for ($i = 0; $i < count($queue); $i++) {
		$html_text .= "<p>".$queue[$i]['filename']."</p>";
	}
	return $html_text;
}

function requests_to_html($requests) {
	$html_text = "";
	for ($i = 0; $i < count($requests); $i++) {
		$html_text .= "<p>".$requests[$i]['id']." - ".$requests[$i]['name']."</p>";
	}
	return $html_text;
}
?>