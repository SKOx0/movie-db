<?php
	$json_file = "../convert_queue.json";
	if (file_exists($json_file)) {
		$queue = json_decode(file_get_contents($json_file), true);
		
		if (count($queue) > 0) {
			$curr_movie = $queue[0];
			$quality = $curr_movie['quality'];
			$filename = $curr_movie['filename'];
			$orig = $curr_movie['orig'];
			$email = $curr_movie['email'];
			
			if (!file_exists("../logs")) {
				exec("mkdir ../logs");
			}
			
			exec("nohup ./convert_video.sh ".escapeshellarg($quality)." ".escapeshellarg($filename)." ".escapeshellarg($orig)." ".escapeshellarg($email)." >../logs/converter.log 2>&1 &");
			
			array_splice($queue, 0, 1);
			
			if (count($queue) == 0) {
				exec("rm ".escapeshellarg($json_file));
			}
			else {
				file_put_contents($json_file, json_encode($queue));
			}
		}
	}
	else {
		exit;
	}
?>