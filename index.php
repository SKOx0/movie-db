<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Movie Collection</title>
	<link rel="stylesheet" href="css/fonts.css">
	<link rel="stylesheet" href="css/index.css">
	<script src="js/openlink.js" defer="defer"></script>
	<script src="js/index.js" defer="defer"></script>
	<script src="js/add.js" defer="defer"></script>
	<script src="js/types.js" defer="defer"></script>
	<?php
		require_once 'mobile_detect/Mobile_Detect.php';
		$detect = new Mobile_Detect;
	
		$order;
		$search;
		$query;
		$count;
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
<body>
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
			<form style="display:inline" id="save_button" method="post" action="scripts/insert" onsubmit="return createJSON()">
				<input type="hidden" name="json" value="">
				<input type="submit" value="Save">
			</form>
			<button style="display:inline" onclick="addMovie()">Add more</button>
			<button style="display:inline" onclick="hideAddOverlay()">Cancel</button>
		</div>
		<p id="json_holder" style="display:none"><?php echo json_encode($qualities); ?></p>
	</div>
	<div id="overlay_edit">
		<form id="edit_form" style="display: inline" action="scripts/update" method="post" name="movie_form" onsubmit="return validateForm()">
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
			<input type="submit" value="Save">
		</form>
		<button style="display: inline" onclick="hideEditOverlay()">Cancel</button>
	</div>
	<div id="overlay_restore">
		<form style="display:inline" action="scripts/restore.php" method="post" enctype="multipart/form-data">
			<input type="file" name="file">
			<input type="submit" name="submit" value="Restore">
		</form>
		<button style="display:inline" onclick="hideRestoreOverlay()">Cancel</button>
	</div>
	<div>
		<table id="header_layout">
			<tr>
				<td id="header_layout_left">
					<?php
						if ($detect->isMobile()) {
					?>
							<a id="load_posters" class="topMenu" onclick="loadPosters()"><button>Load Posters</button></a>
					<?php
						}
					?>
					<?php
						if ($edit == "true") {
					?>
							<a class="topMenu" onclick="showAddOverlay()"><button>Add Movie</button></a>
					<?php
						}
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
				</td>
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
				<input id="search_bar" type="search" name="search" placeholder="Search..." size="40">
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
				<div class="movie_box">
					<table>
						<tr>
							<td>
								<a href="<?php echo $link ?>"><img id="<?php echo $id; ?>" class="posters" src="images/white.jpg" alt="Poster" width="110" height="162.962962963" /></a>
							</td>
							<td>
									<p><span class="name_span"><b><?php echo $name ?></b></span> (<?php echo $year ?>)</p>
									<p><span class="rating_span"><?php echo $rating ?></span> <?php echo $time ?></p>
									<p class="genre_p"><?php echo $genre ?></p>
									<p>
										<?php
											if($count_play > 0){
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
												<form class="topMenu" method="post" action="scripts/delete">
													<input type="hidden" name="id" value="<?php echo $id ?>">
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
        ?>
				<p class="footerInfo"><?php echo exec("git describe"); ?> (<?php echo $branch; ?>)</p>
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
	<?php
		if ($detect->isMobile()) {
	?>
			<script>
				window.onload = afterPageLoadMobile; 
			</script>
	<?php
		}
		else {
	?>
			<script>
				window.onload = afterPageLoadDesktop;
			</script>
	<?php
		}
	?>
</body>
</html>
