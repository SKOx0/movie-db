<?php
	if ((isset($_POST['quality'])) && (isset($_POST['filename'])) && (isset($_POST['email']))) {
		$quality = $_POST['quality'];
		$filename = $_POST['filename'];
		$email = $_POST['email'];
		$json_file = "../convert_queue.json";
		$queue;
		
		$curr_movie = array(
		    "quality" => $quality,
		    "filename" => $filename,
			"email" => $email,
		);
		
		if (file_exists($json_file)) {
			$queue = json_decode(file_get_contents($json_file), true);
		}
		else {
			$queue = array();
		}
		
		print_r($queue);
		
		array_push($queue, $curr_movie);
		file_put_contents($json_file, json_encode($queue));
		
		exec("php -f converter.php");
		
		header("Location: ../");
	}
?>