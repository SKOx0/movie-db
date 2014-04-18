<?php
	$failed_json_file = "../failed_queue.json";
	
	function sendmail($to, $subject, $message) {
		$headers = "From: \"Movie DB\" <movies@virajchitnis.com>"."\r\n".
			"MIME-Version: 1.0"."\r\n".
			"Content-Type: text/html";

		mail($to, $subject, $message, $headers);
	}
	
	if (file_exists($failed_json_file)) {
		$failed_queue = json_decode(file_get_contents($failed_json_file), true);
		
		while (count($failed_queue) > 0) {
			$curr_movie = $failed_queue[0];
			$quality = $curr_movie['quality'];
			$filename = $curr_movie['filename'];
			$orig = $curr_movie['orig'];
			$email = $curr_movie['email'];
			
			if (file_exists("../converted/".$quality."/".$filename)) {
				$failed_queue = json_decode(file_get_contents($failed_json_file), true);
				array_splice($failed_queue, 0, 1);
				
				if (count($failed_queue) == 0) {
					exec("rm ".escapeshellarg($failed_json_file));
				}
				else {
					file_put_contents($failed_json_file, json_encode($failed_queue));
				}
				
				$subject = $filename." converted!";
				$message = "<p>The ".$quality." version of ".$filename." is ready.</p><p><a href=\"http://movies.virajchitnis.com\">Movie DB</a> by <a href=\"http://www.virajchitnis.com\">Viraj Chitnis</a></p><p>&nbsp;</p><p><font size=\"2\" color=\"grey\">Do not reply to this email, this email address does not accept incoming mail. To report bugs or for any queries, email me at <a href=\"mailto:chitnisviraj@gmail.com\">chitnisviraj@gmail.com</a>.</font></p>";
				sendmail($email, $subject, $message);
			}
		}
	}
?>