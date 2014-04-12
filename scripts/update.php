<?php
	if((isset($_POST["id"]))&&(isset($_POST["quality"]))){
		$id = $_POST["id"];
		$quality = $_POST["quality"];
		
		include '../config/config.php';
		$db = new mysqli($HOSTNAME, $USERNAME, $PASSWORD, $DATABASE);
		
		if($db->connect_errno > 0){
		    die('Unable to connect to database [' . $db->connect_error . ']');
		}
		
		$db_movies = $db->prepare("UPDATE Movies SET quality = ? WHERE id = ?;");
		$db_movies->bind_param('ss', $quality, $id);
		$db_movies->execute();
		$db_movies->free_result();

		if((isset($_POST["file_name"]))){
			$file_name = $_POST["file_name"];
			
			if (!empty($file_name)) {
				$db_count = $db->prepare("SELECT count(id) FROM Files WHERE id = ?;");
				$db_count->bind_param('s', $id);
				$db_count->execute();
				$db_count->bind_result($count_id);
				$db_count->fetch();
				$db_count->free_result();

				if($count_id > 0){
					$db_files = $db->prepare("UPDATE Files SET file_name = ? WHERE id = ?;");
					$db_files->bind_param('ss', $file_name, $id);
					$db_files->execute();
					$db_files->free_result();
				}
				else{
					$db_files = $db->prepare("INSERT INTO Files VALUES(?, ?);");
					$db_files->bind_param('ss', $id, $file_name);
					$db_files->execute();
					$db_files->free_result();
				}
			}
		}
		
		$db->close();
		
		header('Location: ../');
	}
	else{
		header('Location: ../');
	}
?>
