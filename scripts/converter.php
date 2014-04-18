<?php
	$json_file = "../convert_queue.json";
	
	function sendmail($to, $subject, $message) {
		$headers = "From: \"Movie DB\" <movies@virajchitnis.com>"."\r\n".
			"MIME-Version: 1.0"."\r\n".
			"Content-Type: text/html"

		mail($to, $subject, $message, $headers);
	}
	
	if (file_exists($json_file)) {
		exec("touch ../converting");
		$queue = json_decode(file_get_contents($json_file), true);
		
		while (count($queue) > 0) {
			$curr_movie = $queue[0];
			$quality = $curr_movie['quality'];
			$filename = $curr_movie['filename'];
			$orig = $curr_movie['orig'];
			$email = $curr_movie['email'];
			
			$subject = "Converting ".$filename;
			$message = "<p>The conversion of ".$filename." to ".$quality." has started. You will receive another email informing you of its completion.</p><p>&nbsp;</p><p><a href=\"http://movies.virajchitnis.com\">Movie DB</a> by <a href=\"http://www.virajchitnis.com\">Viraj Chitnis</a></p><p>&nbsp;</p><p><font size=\"2\" color=\"grey\">Do not reply to this email, this email address does not accept incoming mail. To report bugs or for any queries, email me at <a href=\"mailto:chitnisviraj@gmail.com\">chitnisviraj@gmail.com</a>.</font></p>";
			sendmail($email, $subject, $message);
			
			$time_start = microtime(true);
			
			exec("./convert_video.sh ".escapeshellarg($quality)." ".escapeshellarg($filename)." ".escapeshellarg($orig)." ".escapeshellarg($email));
			
			$time_end = microtime(true);
			$time = intval($time_end) - intval($time_start);
			
			if ($time < 300) {
				$failed_queue;
				$failed_json_file = "../failed_queue.json";
		
				if (file_exists($failed_json_file)) {
					$failed_queue = json_decode(file_get_contents($failed_json_file), true);
				}
				else {
					$failed_queue = array();
				}
		
				array_push($failed_queue, $curr_movie);
				file_put_contents($failed_json_file, json_encode($failed_queue));
				
				if (file_exists("../converted/".$quality."/".$filename)) {
					exec("rm ".escapeshellarg("../converted/".$quality."/".$filename));
				}
				
				$subject = "Converting ".$filename." failed!";
				$message = "<p>The conversion of ".$filename." to ".$quality." has failed. Manual intervention is required.</p><p>&nbsp;</p><p><a href=\"http://movies.virajchitnis.com\">Movie DB</a> by <a href=\"http://www.virajchitnis.com\">Viraj Chitnis</a></p><p>&nbsp;</p><p><font size=\"2\" color=\"grey\">Do not reply to this email, this email address does not accept incoming mail. To report bugs or for any queries, email me at <a href=\"mailto:chitnisviraj@gmail.com\">chitnisviraj@gmail.com</a>.</font></p>";
				sendmail("\"Viraj Chitnis\" <chitnisviraj@gmail.com>", $subject, $message);
			}
			else {
				$subject = $filename." converted!";
				$message = "<p>The ".$quality." version of ".$filename." is ready.</p><p>&nbsp;</p><p><a href=\"http://movies.virajchitnis.com\">Movie DB</a> by <a href=\"http://www.virajchitnis.com\">Viraj Chitnis</a></p><p>&nbsp;</p><p><font size=\"2\" color=\"grey\">Do not reply to this email, this email address does not accept incoming mail. To report bugs or for any queries, email me at <a href=\"mailto:chitnisviraj@gmail.com\">chitnisviraj@gmail.com</a>.</font></p>";
				sendmail($email, $subject, $message);
			}
			
			$queue = json_decode(file_get_contents($json_file), true);
			array_splice($queue, 0, 1);
			
			if (count($queue) == 0) {
				exec("rm ".escapeshellarg($json_file));
			}
			else {
				file_put_contents($json_file, json_encode($queue));
			}
		}
		exec("rm ../converting");
	}
?>