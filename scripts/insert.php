<?php
	function downloadFile ($url, $path) {
		$newfname = $path;
		$file = fopen ($url, "rb");
		
		if ($file) {
			$newf = fopen ($newfname, "wb");
			if ($newf)
			while(!feof($file)) {
				fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
			}
		}

		if ($file) {
			fclose($file);
		}

		if ($newf) {
			fclose($newf);
		}
	}
	
	function addMovie ($id, $quality, $file_name) {
		include '../config/config.php';
		$connection = mysql_connect($HOSTNAME,$USERNAME,$PASSWORD) or die('Connection failed!');
		mysql_select_db($DATABASE,$connection) or die('Database select failed!');
		$query = 'SELECT count(id) FROM Movies WHERE id=\''.$id.'\'';
		$result = mysql_query($query,$connection) or die('Update failed!');
		$count_id = mysql_result($result,0,'count(id)');
		
		if($count_id == 0){
			$source = file_get_contents('http://www.omdbapi.com/?i='.$id);
			$info = json_decode($source, TRUE);
		
			if(!file_exists("../posters")) {
				exec("mkdir ../posters");
			}
		
			if(!file_exists("../posters/backup")) {
				exec("mkdir ../posters/backup");
			}
		
			if (file_exists("../posters/".$id.".jpg")) {
				exec("rm ../posters/".$id.".jpg");
			}
			if (file_exists("rm ../posters/backup/".$id.".jpg")) {
				exec("rm ../posters/backup/".$id.".jpg");
			}
		
			$poster = 'posters/'.$id.'.jpg';
			downloadFile($info['Poster'], "../".$poster);
			$name = $info['Title'];
			$year = $info['Year'];
			$time = $info['Runtime'];
			$genre = $info['Genre'];
			$rating = $info['Rated'];
			$link = 'http://www.imdb.com/title/'.$id;
		
			// Convert poster to progressive JPEG
			exec("mv ../posters/".$id.".jpg ../posters/backup/".$id.".jpg");
			exec("convert -strip -interlace Plane -thumbnail 40.5 ../posters/backup/".$id.".jpg ../posters/".$id.".jpg");
		
			$query = 'INSERT INTO Movies VALUES(\''.$id.'\',\''.$poster.'\',\''.mysql_real_escape_string($name).'\',\''.$year.'\',\''.$time.'\',\''.$genre.'\',\''.$rating.'\',\''.$quality.'\',\''.$link.'\')';
		
			$result = mysql_query($query,$connection) or die('Insert failed!');
			
			if (!empty($file_name)) {
				$query = 'INSERT INTO Files VALUES(\''.$id.'\',\''.mysql_real_escape_string($file_name).'\')';
				$result = mysql_query($query,$connection) or die('Update failed!');
			}
			
			mysql_close($connection);
		}
	}

	if (isset($_POST['json'])) {
		$movies = json_decode($_POST['json']);
		print_r($movies);
		
		echo "<br>";
		
		for ($i = 0; i < count($movies); $i++) {
			$id = $movies[$i]['id'];
			$quality = $movies[$i]['quality'];
			$filename = $movies[$i]['filename'];
			
			print_r($id);
			echo "<br>";
			print_r($quality);
			echo "<br>";
			print_r($filename);
			echo "<br>";
			
			//addMovie($id, $quality, $filename);
		}
		
		//header('Location: ../');
	}
	else if((isset($_POST["id"]))&&(isset($_POST["quality"]))){
		$id = $_POST["id"];
		$quality = $_POST["quality"];
		
		include '../config/config.php';
		$connection = mysql_connect($HOSTNAME,$USERNAME,$PASSWORD) or die('Connection failed!');
		mysql_select_db($DATABASE,$connection) or die('Database select failed!');
		$query = 'SELECT count(id) FROM Movies WHERE id=\''.$id.'\'';
		$result = mysql_query($query,$connection) or die('Update failed!');
		$count_id = mysql_result($result,0,'count(id)');
		
		if($count_id == 0){
			$source = file_get_contents('http://www.omdbapi.com/?i='.$id);
			$info = json_decode($source, TRUE);
		
			if(!file_exists("../posters")) {
				exec("mkdir ../posters");
			}
		
			if(!file_exists("../posters/backup")) {
				exec("mkdir ../posters/backup");
			}
		
			if (file_exists("../posters/".$id.".jpg")) {
				exec("rm ../posters/".$id.".jpg");
			}
			if (file_exists("rm ../posters/backup/".$id.".jpg")) {
				exec("rm ../posters/backup/".$id.".jpg");
			}
		
			$poster = 'posters/'.$id.'.jpg';
			downloadFile($info['Poster'], "../".$poster);
			$name = $info['Title'];
			$year = $info['Year'];
			$time = $info['Runtime'];
			$genre = $info['Genre'];
			$rating = $info['Rated'];
			$link = 'http://www.imdb.com/title/'.$id;
		
			// Convert poster to progressive JPEG
			exec("mv ../posters/".$id.".jpg ../posters/backup/".$id.".jpg");
			exec("convert -strip -interlace Plane -thumbnail 40.5 ../posters/backup/".$id.".jpg ../posters/".$id.".jpg");
		
			$query = 'INSERT INTO Movies VALUES(\''.$id.'\',\''.$poster.'\',\''.mysql_real_escape_string($name).'\',\''.$year.'\',\''.$time.'\',\''.$genre.'\',\''.$rating.'\',\''.$quality.'\',\''.$link.'\')';
		
			$result = mysql_query($query,$connection) or die('Insert failed!');

			if((isset($_POST["file_name"]))){
				$file_name = $_POST["file_name"];
			
				if (!empty($file_name)) {
					$query = 'INSERT INTO Files VALUES(\''.$id.'\',\''.mysql_real_escape_string($file_name).'\')';
					$result = mysql_query($query,$connection) or die('Update failed!');
				}
			}
		
			mysql_close($connection);
		}
		
		header('Location: ../');
	}
	else{
		header('Location: ../');
	}
?>
