<?php
	if ((isset($_POST['quality'])) && (isset($_POST['filename'])) && (isset($_POST['email']))) {
		$quality = $_POST['quality'];
		$filename = $_POST['filename'];
		$email = $_POST['email'];
		
		$queue = array();
		$curr_movie = array(
		    "quality" => $quality,
		    "filename" => $filename,
			"email" => $email,
		);
		array_push($queue, $curr_movie);
		
		echo json_encode($queue)."<br><br>";
		
		echo $quality."<br>";
		echo $filename."<br>";
		echo $email."<br>";
	}
?>