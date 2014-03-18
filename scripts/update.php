<?php
	if((isset($_POST["id"]))&&(isset($_POST["quality"]))){
		include '../config/config.php';
		$connection = mysql_connect($HOSTNAME,$USERNAME,$PASSWORD) or die('Connection failed!');
		mysql_select_db($DATABASE,$connection) or die('Database select failed!');
		$id = $_POST["id"];
		$quality = $_POST["quality"];
		
		$query = 'UPDATE Movies SET quality=\''.$quality.'\' WHERE id=\''.$id.'\'';
		
		$result = mysql_query($query,$connection) or die('Update failed!');

		if((isset($_POST["file_name"]))){
			$file_name = $_POST["file_name"];

			$query = 'SELECT count(id) FROM Files WHERE id=\''.$id.'\'';
			$result = mysql_query($query,$connection) or die('Update failed!');
			$count_id = mysql_result($result,0,'count(id)');

			if($count_id > 0){
				$query = 'UPDATE Files SET file_name=\''.mysql_real_escape_string($file_name).'\' WHERE id=\''.$id.'\'';
				$result = mysql_query($query,$connection) or die('Update failed!');
			}
			else{
				$query = 'INSERT INTO Files VALUES(\''.$id.'\',\''.mysql_real_escape_string($file_name).'\')';
				$result = mysql_query($query,$connection) or die('Update failed!');
			}
		}
		
		mysql_close($connection);
		
		header('Location: ./');
	}
	else{
		echo 'No field must be empty, please go back and fill all fields.';
	}
?>
