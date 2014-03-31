<html>
	<head>
		<script src="js/openlink.js"></script>
		<script src="js/add.js"></script>
		<script src="js/types.js"></script>
		<title>Add a movie</title>
		<?php
			$qualities = array();
		?>
	</head>
	<body>
		<div>
			<form id="movies_list">
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
							array_push($qualities, $value);
							?>
							<option value="<?php echo $value ?>"><?php echo $value ?></option>
							<?php
						}
						mysql_close($connection);
					?>
				</select>
				<input type="text" name="file_name" placeholder="File name">
			</form>
		</div>
		<div>
			<form style="display:inline" id="save_button" method="post" action="scripts/insert.php" onsubmit="return createJSON()">
				<input type="hidden" name="json" value="">
				<input type="submit" value="Save">
			</form>
			<button style="display:inline" onclick="addMovie()">Add more</button>
			<button style="display:inline" onclick="openLink('.')">Cancel</button>
		</div>
		<p id="json_holder" style="display:none"><?php echo json_encode($qualities); ?></p>
	</body>
</html>