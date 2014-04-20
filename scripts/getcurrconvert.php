<?php
	$json_file = "../convert_queue.json";
	$queue;

	if (file_exists($json_file)) {
		$queue = json_decode(file_get_contents($json_file), true);
		echo $queue[0]['filename'];
	}
	else {
		echo "Nothing being converted.";
	}
?>