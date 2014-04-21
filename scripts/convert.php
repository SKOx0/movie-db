<?php
	if ((isset($_POST['quality'])) && (isset($_POST['filename'])) && (isset($_POST['orig'])) && (isset($_POST['email']))) {
		$quality = $_POST['quality'];
		$filename = $_POST['filename'];
		$orig = $_POST['orig'];
		$email = $_POST['email'];
		$json_file = "../convert_queue.json";
		$queue;
		
		$curr_movie = array(
		    "quality" => $quality,
		    "filename" => $filename,
			"orig" => $orig,
			"email" => $email,
		);
		
		if (file_exists($json_file)) {
			$queue = json_decode(file_get_contents($json_file), true);
		}
		else {
			$queue = array();
		}
		
		array_push($queue, $curr_movie);
		file_put_contents($json_file, json_encode($queue));
		
		if (!file_exists("../logs")) {
			exec("mkdir ../logs");
		}
		
		if (!file_exists("../converting")) {
			exec("nohup php -f converter.php >../logs/converter.log 2>&1 &");
		}
	}
	else {
		header("Location: ../");
	}
?>