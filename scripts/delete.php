<?php
	if(isset($_POST["id"])){
		$id = $_POST["id"];
		
		include '../config/config.php';
		$db = new mysqli($HOSTNAME, $USERNAME, $PASSWORD, $DATABASE);
		
		if($db->connect_errno > 0){
		    die('Unable to connect to database [' . $db->connect_error . ']');
		}
		
		exec("rm ../posters/".$id.".jpg");
		exec("rm ../posters/backup/".$id.".jpg");
		
		$db_movies = $db->prepare("DELETE FROM Movies WHERE id = ?;");
		$db_movies->bind_param('s', $id);
		$db_movies->execute();
		$db_movies->free_result();
		
		$db_files = $db->prepare("DELETE FROM Files WHERE id = ?;");
		$db_files->bind_param('s', $id);
		$db_files->execute();
		$db_files->free_result();
		
		$db->close();
		
		header('Location: ../');
	}
	else {
		header('Location: ../');
	}
?>