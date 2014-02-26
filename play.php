<!DOCTYPE html>
<html>
	<head>
		<title>Watch Movies</title>
		<?php
			$link;
			$mod_link;
			if(isset($_GET["id"])){
				$id = $_GET["id"];

				include 'config.php';
				$connection = mysql_connect($HOSTNAME,$USERNAME,$PASSWORD) or die('Connection failed!');
				mysql_select_db($DATABASE,$connection) or die('Database select failed!');

				$query = 'SELECT quality FROM Movies WHERE id=\''.$id.'\'';
				$result = mysql_query($query,$connection) or die('Select failed!');
				$quality = mysql_result($result,0,'quality');

				$query = 'SELECT file_name FROM Files WHERE id=\''.$id.'\'';
				$result = mysql_query($query,$connection) or die('Select failed!');
				$file_name = mysql_result($result,0,'file_name');

				//echo "<script type='text/javascript'>alert('$file_name');</script>";

				$link_quality;
				if($quality == '1080p HD'){
					$link_quality = 'iTunes Movies (1080p HD)';
				}
				if($quality == '720p HD'){
					$link_quality =	'iTunes	Movies (720p HD)';
				}
				if($quality == 'SD'){
					$link_quality =	'iTunes	Movies (SD)';
				}

				$link = 'movies/'.urlencode($link_quality).'/'.urlencode($file_name);
				$mod_link = str_replace("+", "%20", $link);

				mysql_close($connection);
			}
			else{
				header('Location: ./');
			}
		?>
	</head>
	<body>
		<div style="height:100%;width:100%;">
			<video controls>
				<source src="<?php echo $mod_link ?>" type="video/mp4">
				<!--<source src="movies/iTunes%20Movies%20%281080p%20HD%29/Big%20Buck%20Bunny.m4v" type="video/mp4">-->
			</video>
		</div>
	</body>
</html>
