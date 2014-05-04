<!DOCTYPE html>
<html>
<head>
	<?php
		require_once 'mobile_detect/Mobile_Detect.php';
		$detect = new Mobile_Detect;
	?>
	<meta charset="UTF-8">
	<title>Movie Collection</title>
	<?php
		if($detect->isMobile() && !$detect->isTablet()){
	?>
			<link rel="stylesheet" href="css/fonts-mobile.css">
			<link rel="stylesheet" href="css/index-mobile.css">
	<?php
		}
		else if ($detect->isTablet()) {
	?>
			<link rel="stylesheet" href="css/fonts-tablet.css">
			<link rel="stylesheet" href="css/index-tablet.css">
	<?php
		}
		else {
	?>
			<link rel="stylesheet" href="css/fonts.css">
			<link rel="stylesheet" href="css/index.css">
	<?php
		}
		
		if($detect->isiOS()){
	?>
			<meta name="apple-mobile-web-app-title" content="Movies">
			<link rel="apple-touch-icon" href="images/icon.png" />
	<?php
		}
	?>
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="viewport" content="user-scalable=no">
	<link rel="icon" type="image/png" href="images/icon.png">
	<?php
		$order;
		$search;
		$query;
		$count;
		$user = "blank";
		if (isset($_SERVER['PHP_AUTH_USER'])) {
			$user = $_SERVER['PHP_AUTH_USER'];
		}
		$converter_qualities = array("SD", "720p HD", "1080p HD");
		$json_file = "convert_queue.json";
		$failed_json_file = "failed_queue.json";
		$queue;
		$failed_queue;
		if (file_exists($json_file)) {
			$queue = json_decode(file_get_contents($json_file), true);
		}
		if (file_exists($failed_json_file)) {
			$failed_queue = json_decode(file_get_contents($failed_json_file), true);
		}
		
		function is_in_queue($filename, $dest_qual) {
			global $queue;
			if (isset($queue)) {
				for ($i = 0; $i < count($queue); $i++) {
					if (($filename == $queue[$i]['filename']) && ($dest_qual == $queue[$i]['quality'])) {
						return true;
					}
				}
			}
			return false;
		}
		
		function is_in_failed_queue($filename, $dest_qual) {
			global $failed_queue;
			if (isset($failed_queue)) {
				for ($i = 0; $i < count($failed_queue); $i++) {
					if (($filename == $failed_queue[$i]['filename']) && ($dest_qual == $failed_queue[$i]['quality'])) {
						return true;
					}
				}
			}
			return false;
		}
		
		function is_converting($filename, $dest_qual) {
			global $queue;
			if (isset($queue)) {
				if (count($queue) > 0) {
					if (($filename == $queue[0]['filename']) && ($dest_qual == $queue[0]['quality'])) {
						return true;
					}
				}
			}
			return false;
		}
		
		if(isset($_GET["order"])){
			$order = $_GET["order"];
		}
		else{
			$order = 'name';
		}
		
		if(isset($_GET["edit"])){
			$edit = $_GET["edit"];
		}
		else{
			$edit = "false";
		}
		
		if(isset($_GET["search"])){
			$search = $_GET["search"];
			
			if (empty($search)) {
				$redir_get = "";
				if ((isset($_GET["order"])) && (isset($_GET["edit"]))) {
					$redir_get = "?order=".$order."&edit=".$edit;
				}
				if (isset($_GET["order"])) {
					$redir_get = "?order=".$order;
				}
				if (isset($_GET["edit"])) {
					$redir_get = "?edit=".$edit;
				}
				
				header("Location: ./".$redir_get);
			}
			
			$query = 'SELECT * FROM Movies WHERE name LIKE \'%'.$search.'%\' ORDER BY '.$order;
		}
		else{
			$query = 'SELECT * FROM Movies ORDER BY '.$order;
		}
	?>
</head>
<body onload="writeCopyrightYear()">
	<div id="overlay_background"></div>
	<div id="overlay_add">
		<div>
			<form id="movies_list">
				<input type="text" name="id" placeholder="IMDB ID" size="9">
				<select name="quality">
					<?php
						$qualities = array();
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
				<input type="text" name="file_name" placeholder="File name" size="25">
			</form>
		</div>
		<div>
			<button style="display:inline" onclick="createJSON()">Save</button>
			<button style="display:inline" onclick="addMovie()">Add more</button>
			<button style="display:inline" onclick="hideAddOverlay()">Cancel</button>
		</div>
		<p id="json_holder" style="display:none"><?php echo json_encode($qualities); ?></p>
	</div>
	<div id="overlay_edit">
		<form id="edit_form" style="display: inline" name="movie_form">
			<input type="text" name="id" placeholder="IMDB ID" size="9" readonly>
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
						?>
						<option value="<?php echo $value ?>"><?php echo $value ?></option>
						<?php
					}
					$quality_table->free_result();
					$db->close();
				?>
			</select>
			<input type="text" name="file_name" placeholder="File name" size="25">
			<br>
		</form>
		<button style="display: inline" onclick="saveEdit()">Save</button>
		<button style="display: inline" onclick="hideEditOverlay()">Cancel</button>
	</div>
	<div id="overlay_restore">
		<form style="display:inline" action="scripts/restore.php" method="post" enctype="multipart/form-data">
			<input type="file" name="file">
			<input type="submit" name="submit" value="Restore">
		</form>
		<button style="display:inline" onclick="hideRestoreOverlay()">Cancel</button>
	</div>
	<div id="overlay_email">
		<p>It can take 2-3 hours to convert a movie. Enter your email address below so that you can be notified as to the progress of your conversion.</p>
		<form id="convert_email_form" style="display:inline">
			<input type="hidden" name="quality">
			<input type="hidden" name="filename">
			<input type="hidden" name="orig">
			<input type="text" name="email" placeholder="Your email" size="25">
		</form>
		<button style="display:inline" onclick="startConversion()">Start conversion</button>
		<button style="display:inline" onclick="hideEmailOverlay()">Cancel</button>
	</div>
	<div id="overlay_progress">
		<p id="converting_movie">&nbsp;</p>
		<div id="ffmpeg_progress">
			<pre>Connection failed, reload page.</pre>
		</div>
		<button onclick="hideProgressOverlay()">Close</button>
	</div>
	<div id="overlay_request">
		<p>Requesting a movie does not guarantee its addition to the database. There is also no time frame for movie addition, it could take anywhere from a few hours to a few months before the movie you request gets added, depending upon many factors regarding the movie. If the movie you request gets added to the database, you will be notified as such.<br>Enter the movie's IMDB ID or name, or both so that the movie can be identified.</p>
		<form id="request_movie_form" style="display:inline">
			<input type="text" name="id" placeholder="IMDB ID" size="9">
			or
			<input type="text" name="name" placeholder="Movie name" size="25">
			<input type="text" name="email" placeholder="Your email" size="25">
		</form>
		<button style="display:inline" onclick="sendRequest()">Send request</button>
		<button style="display:inline" onclick="hideRequestOverlay()">Cancel</button>
	</div>
	<div>
		<table id="header_layout">
			<tr>
				<?php
					if (($detect->isTablet()) || (!$detect->isMobile())) {
				?>
						<td id="header_layout_left">
							<a class="topMenu" onclick="showRequestOverlay()"><button>Request a Movie</button></a>
							<?php
								if ($edit == "true") {
							?>
									<a class="topMenu" onclick="showAddOverlay()"><button>Add/Edit Movies</button></a>
							<?php
								}
								if (($user == "viraj") || ($user == "blank")) {
							?>
									<form class="topMenu" method="get" action="./">
									<?php
										if(isset($_GET["order"])){
									?>
											<input type="hidden" name="order" value="<?php echo $order ?>">
									<?php
										}
										if(isset($_GET["search"])){
									?>
											<input type="hidden" name="search" value="<?php echo $search ?>">
									<?php
										}
										if ($edit == "false") {
									?>
											<input type="hidden" name="edit" value="true">
											<input type="submit" value="Edit mode: Off">
									<?php
										}
										else {
									?>
											<input type="submit" value="Edit mode: On">
									<?php
										}
									?>
									</form>
							<?php
								}
								if (isset($queue)) {
							?>
									<button class="topMenu" onclick="showProgressOverlay()">Conversion Progress</button>
							<?php
								}
							?>
						</td>
				<?php
					}
				?>
				<td id="header_layout_right">
					<form id="orderForm" class="topMenu" method="get" action="./">
						<?php
							if(isset($_GET["search"])){
						?>
								<input type="hidden" name="search" value="<?php echo $search ?>">
						<?php
							}
							if(isset($_GET["edit"])){
						?>
								<input type="hidden" name="edit" value="<?php echo $edit ?>">
						<?php
							}
						?>
							Order by <select name="order" onchange="submitOrderForm()">
								<?php
									if (isset($_GET['order'])) {
										if ($order == "name") {
								?>
											<option value="name" selected>Name</option>
								<?php
										}
										else {
								?>
											<option value="name">Name</option>
								<?php
										}
										if ($order == "year") {
								?>
											<option value="year" selected>Year</option>
								<?php
										}
										else {
								?>
											<option value="year">Year</option>
								<?php
										}
										if ($order == "time") {
								?>
											<option value="time" selected>Duration</option>
								<?php
										}
										else {
								?>
											<option value="time">Duration</option>
								<?php
										}
										if ($order == "genre") {
								?>
											<option value="genre" selected>Genre</option>
								<?php
										}
										else {
								?>
											<option value="genre">Genre</option>
								<?php
										}
										if ($order == "rating") {
								?>
											<option value="rating" selected>Rating</option>
								<?php
										}
										else {
								?>
											<option value="rating">Rating</option>
								<?php
										}
										if ($order == "quality") {
								?>
											<option value="quality" selected>Quality</option>
								<?php
										}
										else {
								?>
											<option value="quality">Quality</option>
								<?php
										}
									}
									else {
								?>
										<option value="name" selected>Name</option>
										<option value="year">Year</option>
										<option value="time">Duration</option>
										<option value="genre">Genre</option>
										<option value="rating">Rating</option>
										<option value="quality">Quality</option>
								<?php
									}
								?>
							</select>
					</form>
				</td>
			</tr>
		</table>
	</div>
	<div id="search_box">
		<form id="searchForm" method="get" action="./">
		<?php
			if(isset($_GET["order"])){
		?>
				<input type="hidden" name="order" value="<?php echo $order ?>">
		<?php
			}
			if(isset($_GET["edit"])){
		?>
				<input type="hidden" name="edit" value="<?php echo $edit ?>">
		<?php
			}
			if(isset($_GET["search"])){
		?>
				<input id="search_bar" type="search" name="search" value="<?php echo $search ?>" placeholder="Search..." size="40">
		<?php
			}
			else {
		?>
				<input id="search_bar" type="search" name="search" placeholder="Search...">
		<?php
			}
		?>
		</form>
	</div>
	<div id="movie_boxes">
		<?php
			include 'config/config.php';
			$db = new mysqli($HOSTNAME, $USERNAME, $PASSWORD, $DATABASE);
			
			if($db->connect_errno > 0){
			    die('Unable to connect to database [' . $db->connect_error . ']');
			}
			
			$movies_table = $db->prepare($query);
			$movies_table->execute();
			$movies_table->bind_result($id, $poster, $name, $year, $time, $genre, $rating, $quality, $link);
			
			while($movies_table->fetch()){
				$count_play;
				$movie_link;
				if (file_exists("movies")) {
					$db2 = new mysqli($HOSTNAME, $USERNAME, $PASSWORD, $DATABASE);
					$query_play = 'SELECT count(id) FROM Files WHERE id=\''.$id.'\'';
					$files_table = $db2->prepare($query_play);
					$files_table->execute();
					$files_table->bind_result($count_play);
					$files_table->fetch();
					$files_table->free_result();

					$query_name = 'SELECT file_name FROM Files WHERE id=\''.$id.'\'';
					$files_table = $db2->prepare($query_name);
					$files_table->execute();
					$files_table->bind_result($file_name);
					$files_table->fetch();
					$files_table->free_result();
					$db2->close();
					
					$link_quality;
					if($quality == '1080p HD'){
						$link_quality = 'iTunes Movies (1080p HD)';
					}
					if($quality == '720p HD'){
						$link_quality =	'iTunes Movies (720p HD)';
					}
					if($quality == 'SD'){
						$link_quality =	'iTunes Movies (SD)';
					}
					
					$client_ip;
					if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
						$client_ip = $_SERVER['HTTP_CLIENT_IP'];
					}
					elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
						$client_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
					}
					else {
						$client_ip = $_SERVER['REMOTE_ADDR'];
					}
					
					if ((substr($client_ip,0,8) == "192.168.") || ($client_ip == "127.0.0.1")) {
						$movie_link = 'http://nas/shares/Media/Movies/'.rawurlencode($link_quality).'/'.rawurlencode($file_name);
					}
					else {
						$movie_link = 'movies/'.rawurlencode($link_quality).'/'.rawurlencode($file_name);
					}
				}
				else {
					$count_play = 0;
				}
		?>
				<div class="movie_box" id="<?php echo str_replace(" ", "_", $name) ?>">
					<table>
						<tr>
							<td>
								<a href="<?php echo $link ?>"><img id="<?php echo $id; ?>" class="posters lazy" data-src="posters/<?php echo $id; ?>.jpg"></a>
							</td>
							<td>
									<p><span class="name_span"><b><?php echo $name ?></b></span> (<?php echo $year ?>)</p>
									<p><span class="rating_span"><?php echo $rating ?></span> <?php echo $time ?></p>
									<p class="genre_p"><?php echo $genre ?></p>
									<p>
										<?php
											if($count_play > 0){
												for ($i = 0; $i < count($converter_qualities); $i++) {
													if ($quality != $converter_qualities[$i]) {
														if (is_in_failed_queue($file_name, $converter_qualities[$i])) {
										?>
															<button disabled>Queued <?php echo $converter_qualities[$i] ?></button>
										<?php
														}
														elseif (is_converting($file_name, $converter_qualities[$i])) {
										?>
															<button onclick="showProgressOverlay()">Converting <?php echo $converter_qualities[$i] ?></button>
										<?php
														}
														elseif (is_in_queue($file_name, $converter_qualities[$i])) {
										?>
															<button onclick="showProgressOverlay()">Queued <?php echo $converter_qualities[$i] ?></button>
										<?php
														}
														elseif (file_exists("converted/".$converter_qualities[$i]."/".$file_name)) {
															$converted_movie_link = 'converted/'.rawurlencode($converter_qualities[$i]).'/'.rawurlencode($file_name);
										?>
															<a href="<?php echo $converted_movie_link ?>"><button>&#9658; <?php echo $converter_qualities[$i] ?></button></a>
										<?php
														}
														else {
										?>
															<button onclick="convertFile('<?php echo $converter_qualities[$i] ?>', '<?php echo $file_name ?>', '<?php echo $quality ?>')">Convert <?php echo $converter_qualities[$i] ?></button>
										<?php
														}
													}
													else {
														break;
													}
												}
										?>
												<a href="<?php echo $movie_link ?>"><button>&#9658; <?php echo $quality ?></button></a>
										<?php
											}
										?>
									</p>
									<?php
										if ($edit == "true") {
									?>
											<p>
												<form class="topMenu" onsubmit="return showEditOverlay('<?php echo $id ?>', '<?php echo $quality ?>', '<?php echo $file_name ?>')">
													<input type="hidden" name="id" value="<?php echo $id ?>">
													<input type="hidden" name="quality" value="<?php echo $quality ?>">
													<input type="hidden" name="file_name" value="<?php echo $file_name ?>">
													<input type="image" class="formButton" title="Edit" src="images/edit.png">
												</form>
												<form class="topMenu" onsubmit="return confirmDelete('<?php echo $name ?>','<?php echo $id ?>','<?php echo $file_name ?>')">
													<input type="hidden" name="id" value="<?php echo $id ?>">
													<input type="hidden" name="filename" value="<?php echo $file_name ?>">
													<input type="image" class="formButton" title="Delete" src="images/delete.png">
												</form>
											</p>
									<?php
										}
									?>
							</td>
						</tr>
					</table>
				</div>
		<?php
			}
			$movies_table->free_result();
			$query = 'SELECT count(id) FROM Movies';
			$movies_table = $db->prepare($query);
			$movies_table->execute();
			$movies_table->bind_result($count);
			$movies_table->fetch();
			$movies_table->free_result();
			$db->close();
		?>
	</div>
	<div id="footer">
		<p class="footerInfo"><?php echo $count ?> Movies</p>
		<?php
        	if (file_exists(".git")) {
        		$git_branch = exec("git branch | grep '*' | awk '{print $2}'");
        		$branch;
        		if ($git_branch == "master") {
        			$branch = "stable";
        		}
        		else {
        			$branch = $git_branch;
        		}
				
				if ($branch != "stable"){
        ?>
					<p class="footerInfo"><?php echo exec("git describe"); ?> (<?php echo $branch; ?>)</p>
		<?php
				}
		?>
				<p>
				<?php
					if ($edit == "true") {
						if (file_exists('movies.sql')) {
							exec("rm movies.sql");
						}
						if (file_exists('moviedb.mbk')) {
							exec("rm moviedb.mbk");
						}
						include('config/config.php');
						exec("/usr/bin/mysqldump -u ".$USERNAME." -p".$PASSWORD." ".$DATABASE." > movies.sql");
						exec("tar -cf moviedb posters movies.sql");
						exec("gzip -S .mbk moviedb");
				?>
						<button onclick="openLink('scripts/gitupdate')">Update</button> 
						<button onclick="openLink('moviedb.mbk')">Backup</button>
						<button onclick="showRestoreOverlay()">Restore</button>
				<?php
					}
				?>
				</p>
		<?php
			}
		?>
		<p id="copyright">&copy; 2013-<span id="copy_year"></span> Viraj Chitnis</p>
	</div>
	<script src="js/index.js" type="text/javascript"></script>
	<script src="js/openlink.js" type="text/javascript"></script>
	<script src="js/add.js" type="text/javascript"></script>
	<script src="js/types.js" type="text/javascript"></script>
</body>
</html>
