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

	if((isset($_POST["id"]))&&(isset($_POST["quality"]))){
		$id = $_POST["id"];
		$quality = $_POST["quality"];
		
		$source = file_get_contents('http://www.omdbapi.com/?i='.$id);
		$info = json_decode($source, TRUE);
		
		if (file_exists("posters/".$id.".jpg")) {
			exec("rm posters/".$id.".jpg");
		}
		if (file_exists("rm posters/backup/".$id.".jpg")) {
			exec("rm posters/backup/".$id.".jpg");
		}
		
		$poster = 'posters/'.$id.'.jpg';
		downloadFile($info['Poster'], $poster);
		$name = $info['Title'];
		$year = $info['Year'];
		$time = $info['Runtime'];
		$genre = $info['Genre'];
		$rating = $info['Rated'];
		$link = 'http://www.imdb.com/title/'.$id;
		
		// Convert poster to progressive JPEG
		exec("mv posters/".$id.".jpg posters/backup/".$id.".jpg");
		exec("convert -strip -interlace Plane -thumbnail 40.5 posters/backup/".$id.".jpg posters/".$id.".jpg");
		
		include 'config.php';
		$connection = mysql_connect($HOSTNAME,$USERNAME,$PASSWORD) or die('Connection failed!');
		mysql_select_db($DATABASE,$connection) or die('Database select failed!');
		
		$query = 'INSERT INTO Movies VALUES(\''.$id.'\',\''.$poster.'\',\''.mysql_real_escape_string($name).'\',\''.$year.'\',\''.$time.'\',\''.$genre.'\',\''.$rating.'\',\''.$quality.'\',\''.$link.'\')';
		
		echo $query;
		echo '<br>';
		
		$result = mysql_query($query,$connection) or die('Insert failed!');

		if((isset($_POST["file_name"]))){
			$file_name = $_POST["file_name"];

			$query_unesc = 'INSERT INTO Files VALUES(\''.$id.'\',\''.mysql_real_escape_string($file_name).'\')';
			$query = mysql_real_escape_string($query_unesc);
			$result = mysql_query($query,$connection) or die('Update failed!');
		}
		
		mysql_close($connection);
		
		header('Location: ./');
	}
	else{
		echo 'No field must be empty, please go back and fill all fields.';
	}
?>
