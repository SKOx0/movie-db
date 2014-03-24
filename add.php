<html>
	<head>
		<title>Add a movie</title>
	</head>
	<body>
		<form>
			<input type="text" name="id" placeholder="IMDB ID">
			<select name="quality">
				<?php
					include 'config/config.php';
					$connection = mysql_connect($HOSTNAME,$USERNAME,$PASSWORD) or die('Connection failed!');
					mysql_select_db($DATABASE,$connection) or die('Database select failed!');
				
					$result = mysql_query('SELECT * FROM Quality',$connection);
				
					$numrows = mysql_numrows($result);
					for($i = 0; $i < $numrows; $i++){
						$value = mysql_result($result,$i,'quality');
				?>
						<option value="<?php echo $value ?>"><?php echo $value ?></option>
				<?php
					}
					mysql_close($connection);
				?>
			</select>
			<input type="text" name="file_name" placeholder="File name">
		</form>
	</body>
</html>