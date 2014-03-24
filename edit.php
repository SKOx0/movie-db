<!DOCTYPE html>
<html>
<head>
	<script src="js/edit.js"></script>
	<?php
		$id;
		$quality;
		$file_name;
		
		$title;
		$action;
		if(isset($_POST["id"])){
			$id = $_POST["id"];
			
			include 'config/config.php';
			$connection = mysql_connect($HOSTNAME,$USERNAME,$PASSWORD) or die('Connection failed!');
			mysql_select_db($DATABASE,$connection) or die('Database select failed!');
			
			$result = mysql_query('SELECT * FROM Movies WHERE id=\''.$id.'\'',$connection) or die('Select failed!');
			
			$name = mysql_result($result,0,'name');
			$year = mysql_result($result,0,'year');
			$quality = mysql_result($result,0,'quality');

			$result = mysql_query('SELECT count(id) FROM Files WHERE id=\''.$id.'\'',$connection) or die('Select failed!');
			$file_count = mysql_result($result,0,'count(id)');

			if($file_count > 0){
				$result = mysql_query('SELECT file_name FROM Files WHERE id=\''.$id.'\'',$connection) or die('Select failed!');
				$file_name = mysql_result($result,0,'file_name');
			}
			
			mysql_close($connection);
			
			$title = 'Edit '.$name.' ('.$year.')';
			$action = 'scripts/update.php';
		}
		else{
			header('Location: .');
			$id = '';
			$quality = '';
			
			$title = 'Add a movie';
			$action = 'scripts/insert.php';
		}
	?>
	<meta charset="UTF-8">
	<title><?php echo $title ?></title>
	<link rel="stylesheet" href="index.css">
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
				$connection = mysql_connect($HOSTNAME,$USERNAME,$PASSWORD) or die('Connection failed!');
				mysql_select_db($DATABASE,$connection) or die('Database select failed!');
				
				$result = mysql_query('SELECT * FROM Quality',$connection);
				
				$numrows = mysql_numrows($result);
				for($i = 0; $i < $numrows; $i++){
					$value = mysql_result($result,$i,'quality');
					
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
				
				mysql_close($connection);
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
