<html>
	<head>
		<?php
			if ((substr($_SERVER['REQUEST_URI'], -1)) == "/") {
				$action = "../scripts/insert";
		?>
				<script src="../js/openlink.js"></script>
				<script src="../js/add.js"></script>
				<script src="../js/types.js"></script>
		<?php
			}
			else {
				$action = "scripts/insert";
		?>
				<script src="js/openlink.js"></script>
				<script src="js/add.js"></script>
				<script src="js/types.js"></script>
		<?php
			}
		?>
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
						$db = new mysqli($HOSTNAME, $USERNAME, $PASSWORD, $DATABASE);
			
						if($db->connect_errno > 0){
						    die('Unable to connect to database [' . $db->connect_error . ']');
						}
						
						$quality_table = $db->prepare('SELECT * FROM Quality');
						$quality_table->execute();
						$quality_table->bind_result($value);
						
						while($quality_table->fetch()){
							array_push($qualities, $value);
							?>
							<option value="<?php echo $value ?>"><?php echo $value ?></option>
							<?php
						}
						$quality_table->free_result();
						$db->close();
					?>
				</select>
				<input type="text" name="file_name" placeholder="File name">
			</form>
		</div>
		<div>
			<form style="display:inline" id="save_button" method="post" action="<?php echo $action; ?>" onsubmit="return createJSON()">
				<input type="hidden" name="json" value="">
				<input type="submit" value="Save">
			</form>
			<button style="display:inline" onclick="addMovie()">Add more</button>
			<button style="display:inline" onclick="openLink('.')">Cancel</button>
		</div>
		<p id="json_holder" style="display:none"><?php echo json_encode($qualities); ?></p>
	</body>
</html>