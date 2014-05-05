<?php
include '../config/config.php';

$db = new mysqli($HOSTNAME, $USERNAME, $PASSWORD, $DATABASE);

if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}

$count_fixes = 0;

function compare_movies ($files, $quality) {
	global $db;
	global $count_fixes;
	for ($i = 0; $i < count($files); $i++) {
		if ((substr($files[$i], 0, 1) === '.') || (substr($files[$i], 0, 1) === '_')) {
			continue;
		}
		
		$id_table = $db->prepare("SELECT count(id) FROM Files WHERE file_name = ?;");
		$id_table->bind_param('s', $files[$i]);
		$id_table->execute();
		$id_table->bind_result($count_id);
		$id_table->fetch();
		$id_table->free_result();
		
		if ($count_id > 0) {
			$files_table = $db->prepare("SELECT id FROM Files WHERE file_name = ?;");
			$files_table->bind_param('s', $files[$i]);
			$files_table->execute();
			$files_table->bind_result($id);
			$files_table->fetch();
			$files_table->free_result();
		
			$movies_table = $db->prepare("SELECT quality FROM Movies WHERE id = ?;");
			$movies_table->bind_param('s', $id);
			$movies_table->execute();
			$movies_table->bind_result($curr_quality);
			$movies_table->fetch();
			$movies_table->free_result();
		
			if ($quality != $curr_quality) {
				echo "<b>".$files[$i]."</b> (".$curr_quality.")";
				$update_table = $db->prepare("UPDATE Movies SET quality = ? WHERE id = ?;");
				$update_table->bind_param('ss', $quality, $id);
				$update_table->execute();
				$update_table->free_result();
				$count_fixes++;
				echo " ==> (".$quality.")<br>";
			}
		}
	}
}

$files_1080p = scandir("../movies/iTunes Movies (1080p HD)/");
$files_720p = scandir("../movies/iTunes Movies (720p HD)/");
$files_SD = scandir("../movies/iTunes Movies (SD)/");

compare_movies($files_1080p, "1080p HD");
compare_movies($files_720p, "720p HD");
compare_movies($files_SD, "SD");

if ($count_fixes == 0) {
	echo "Nothing to repair! Your golden!";
}

$db->close();
?>