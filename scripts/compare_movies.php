<?php
include '../config/config.php';

$db = new mysqli($HOSTNAME, $USERNAME, $PASSWORD, $DATABASE);

if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}

function compare_movies ($files, $quality) {
	for ($i = 0; $i < count($files); $i++) {
		$files_table = $db->prepare("SELECT count(file_name) FROM Files WHERE file_name = ?;");
		$files_table->bind_param('s', $files[$i]);
		$files_table->execute();
		$files_table->bind_result($count_file_name);
		$files_table->fetch();
		$files_table->free_result();
		
		if ($count_file_name == 0) {
			echo $files[$i]." (".$quality")<br>";
		}
	}
}

$files_1080p = scandir("../movies/iTunes Movies (1080p HD)/");
$files_720p = scandir("../movies/iTunes Movies (720p HD)/");
$files_SD = scandir("../movies/iTunes Movies (SD)/");

compare_movies($files_1080p, "1080p HD");
compare_movies($files_720p, "720p HD");
compare_movies($files_SD, "SD");
?>