<?php
	if((isset($_POST["id"]))&&(isset($_POST["quality"]))){
		include 'config.php';
		$connection = mysql_connect($HOSTNAME,$USERNAME,$PASSWORD) or die('Connection failed!');
		mysql_select_db($DATABASE,$connection) or die('Database select failed!');
		$id = $_POST["id"];
		$quality = $_POST["quality"];
		
		$query = 'UPDATE Movies SET quality=\''.$quality.'\' WHERE id=\''.$id.'\'';
		
		$result = mysql_query($query,$connection) or die('Update failed!');
		
		mysql_close($connection);
		
		header('Location: ./');
	}
	else{
		echo 'No field must be empty, please go back and fill all fields.';
	}
?>