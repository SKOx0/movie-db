<?php
	if(isset($_POST["id"])){
		$id = $_POST["id"];
		
		include 'config.php';
		$connection = mysql_connect($HOSTNAME,$USERNAME,$PASSWORD) or die('Connection failed!');
		mysql_select_db($DATABASE,$connection) or die('Database select failed!');
		
		$result = mysql_query('SELECT poster FROM Movies WHERE id=\''.$id.'\'',$connection);
		$poster = mysql_result($result,0,'poster');
		
		exec("rm posters/".$id.".jpg");
		exec("rm posters/backup/".$id.".jpg");
		
		$result = mysql_query('DELETE FROM Movies WHERE id=\''.$id.'\'',$connection) or die('Delete failed!');
		$result = mysql_query('DELETE FROM Files WHERE id=\''.$id.'\'',$connection) or die('Delete failed!');
		mysql_close($connection);
		
		header('Location: ./');
	}
?>