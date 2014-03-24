<?php
	$allowedExts = array(".mbk");
	$temp = explode(".", $_FILES["file"]["name"]);
	$extension = end($temp);
	if (in_array($extension, $allowedExts)) {
		if ($_FILES["file"]["error"] > 0) {
			echo "Error: ".$_FILES["file"]["error"]."<br>";
		}
		else
		{
			echo "Upload: ".$_FILES["file"]["name"]."<br>";
			echo "Type: ".$_FILES["file"]["type"]."<br>";
			echo "Size: ".($_FILES["file"]["size"] / 1024)." kB<br>";
			echo "Stored in: ".$_FILES["file"]["tmp_name"];
			
			if (file_exists("../restore.mbk")) {
				exec("rm ../restore.mbk");
			}
			
			move_uploaded_file($_FILES["file"]["tmp_name"], "../restore.mbk");
			if (file_exists("../restore.mbk")) {
				echo "Stored in: restore.mbk";
			}
		}
	}
	else {
		echo "Invalid file";
	}
?>