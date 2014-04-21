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
		$db = new mysqli($HOSTNAME, $USERNAME, $PASSWORD, $DATABASE);
		
		if($db->connect_errno > 0){
		    die('Unable to connect to database [' . $db->connect_error . ']');
		}
		
		$db_files = $db->prepare("SELECT count(id) FROM Movies WHERE id = ?;");
		$db_files->bind_param('s', $id);
		$db_files->execute();
		$db_files->bind_result($count_id);
		$db_files->fetch();
		$db_files->free_result();
		
		if($count_id == 0){
			$source = file_get_contents('http://www.omdbapi.com/?i='.$id);
			$info = json_decode($source, TRUE);
			$response = $info['Response'];
			
			if ($response == "True") {
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
				exec("convert -strip -interlace Plane -thumbnail 200 ../posters/backup/".$id.".jpg ../posters/".$id.".jpg");
				
				$db_movies = $db->prepare("INSERT INTO Movies VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?);");
				$db_movies->bind_param('sssssssss', $id, $poster, $name, $year, $time, $genre, $rating, $quality, $link);
				$db_movies->execute();
				$db_movies->free_result();
			
				if (!empty($file_name)) {
					$db_files = $db->prepare("INSERT INTO Files VALUES(?, ?);");
					$db_files->bind_param('ss', $id, $file_name);
					$db_files->execute();
					$db_files->free_result();
				}
			}
		}
		$db->close();
	}

	if (isset($_POST['json'])) {
		$movies = json_decode($_POST['json'], true);
		
		for ($i = 0; $i < count($movies); $i++) {
			$id = $movies[$i]['id'];
			$quality = $movies[$i]['quality'];
			$filename = $movies[$i]['filename'];
			
			addMovie($id, $quality, $filename);
		}
	}
	else{
		header('Location: ../');
	}
?>
