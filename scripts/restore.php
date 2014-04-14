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
			
			echo "Deleted old restore.mbk"."<br>";
			
			if (file_exists("../restore")) {
				exec("rm ../restore");
			}
			
			echo "Deleted old uncompressed restore file"."<br>";
			
			if (file_exists("../posters")) {
				exec("rm -r ../posters");
			}
			
			echo "Delete old posters folder"."<br>";
			
			if (file_exists("../movies.sql")) {
				exec("rm ../movies.sql");
			}
			
			echo "Delete old sql file"."<br>";
			
			move_uploaded_file($_FILES["file"]["tmp_name"], "../restore.mbk");
			
			echo "Move uploaded file to proper location"."<br>";
			
			exec("cd ..; /bin/gunzip -S .mbk restore.mbk");
			
			echo "Decompress the restore.mbk file"."<br>";
			
			exec("cd ..; /bin/tar -xf restore");
			
			echo "Untar the restore file"."<br>";
			
			include('../config/config.php');
			exec("cd ..; /usr/bin/mysql -u ".$USERNAME." -p".$PASSWORD." ".$DATABASE." < movies.sql");
			
			header("Location: ../");
		}
	}
	else {
		echo "Invalid file";
	}
?>