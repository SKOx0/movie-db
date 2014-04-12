<!DOCTYPE html>
<html>
<head>
	<?php
		if ((substr($_SERVER['REQUEST_URI'], -1)) == "/") {
			$action_pre = "../";
	?>
			<script src="../js/edit.js"></script>
	<?php
		}
		else {
			$action_pre = "";
	?>
			<script src="js/edit.js"></script>
	<?php
		}
		
		$id;
		$quality;
		$file_name;
		
		$title;
		$action;
		if(isset($_POST["id"])){
			$id = $_POST["id"];
			
			include 'config/config.php';
			$db = new mysqli($HOSTNAME, $USERNAME, $PASSWORD, $DATABASE);
			
			if($db->connect_errno > 0){
			    die('Unable to connect to database [' . $db->connect_error . ']');
			}
			
			$db_movie = $db->prepare("SELECT name, year, quality FROM Movies WHERE id = ?;");
			$db_movie->bind_param('s', $id);
			$db_movie->execute();
			$db_movie->bind_result($name, $year, $quality);
			$db_movie->fetch();
			$db_movie->free_result();
			
			$db_files = $db->prepare("SELECT count(id) FROM Files WHERE id = ?;");
			$db_files->bind_param('s', $id);
			$db_files->execute();
			$db_files->bind_result($file_count);
			$db_files->fetch();
			$db_files->free_result();

			if($file_count > 0){
				$db_filename = $db->prepare("SELECT file_name FROM Files WHERE id = ?;");
				$db_filename->bind_param('s', $id);
				$db_filename->execute();
				$db_filename->bind_result($file_name);
				$db_filename->fetch();
				$db_filename->free_result();
			}
			
			$db->close();
			
			$title = 'Edit '.$name.' ('.$year.')';
			$action = $action_pre.'scripts/update';
		}
		else{
			header('Location: .');
			$id = '';
			$quality = '';
			
			$title = 'Add a movie';
			$action = $action_pre.'scripts/insert';
		}
	?>
	<meta charset="UTF-8">
	<title><?php echo $title ?></title>
</head>
<body>
	<form style="display: inline" action="<?php echo $action ?>" method="post" name="movie_form" onsubmit="return validateForm()">
	<?php
		if(isset($_POST["id"])){
	?>
			IMDB ID: <input type="text" name="id" value="<?php echo $id ?>" readonly>
	<?php
		}
		else{
	?>
			IMDB ID: <input type="text" name="id" value="<?php echo $id ?>">
	<?php
		}
	?>
		Quality: <select name="quality">
			<?php
				if($quality==''){
			?>
					<option selected value="<?php echo $quality ?>">---</option>
			<?php
				}
				
				include 'config/config.php';
				$db = new mysqli($HOSTNAME, $USERNAME, $PASSWORD, $DATABASE);
			
				if($db->connect_errno > 0){
				    die('Unable to connect to database [' . $db->connect_error . ']');
				}
				
				$db_quality = $db->prepare('SELECT * FROM Quality');
				$db_quality->execute();
				$db_quality->bind_result($value);
				
				while($db_quality->fetch()){
					if($quality==$value){
			?>
						<option selected value="<?php echo $value ?>"><?php echo $value ?></option>
			<?php
					}
					else{
			?>
						<option value="<?php echo $value ?>"><?php echo $value ?></option>
			<?php
					}
				}
				$db_quality->free_result();
				$db->close();
			?>
		</select>
		<?php
			if($file_count > 0){
		?>
				File name: <input type="text" name="file_name" value="<?php echo $file_name ?>">
		<?php
			}
			else{
		?>
				File name: <input type="text" name="file_name">
		<?php
			}
		?>
		<br>
		<input type="submit" value="Save">
	</form>
	<a style="display: inline" href="./"><button>Cancel</button></a>
</body>
</html>
