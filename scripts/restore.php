<?php
	$extension = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
	if ($extension === "mbk") {
		if ($_FILES["file"]["error"] > 0) {
			echo "Error: ".$_FILES["file"]["error"]."<br>";
		}
		else
		{
			if (file_exists("../restore.mbk")) {
				exec("rm ../restore.mbk");
			}
			
			if (file_exists("../restore")) {
				exec("rm ../restore");
			}
			
			if (file_exists("../posters")) {
				exec("rm -r ../posters");
			}
			
			if (file_exists("../movies.sql")) {
				exec("rm ../movies.sql");
			}
			
			move_uploaded_file($_FILES["file"]["tmp_name"], "../restore.mbk");
			exec("cd ..; gunzip -S .mbk restore.mbk");
			exec("cd ..; tar -xf restore");
			include('../config/config.php');
			exec("cd ..; /usr/bin/mysqldump -u ".$USERNAME." -p".$PASSWORD." ".$DATABASE." < movies.sql");
		}
	}
	else {
		echo "Invalid file";
	}
?>