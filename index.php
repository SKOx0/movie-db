<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Movie Collection</title>
	<link rel="stylesheet" href="css/fonts.css">
	<link rel="stylesheet" href="css/index.css">
	<script src="js/openlink.js"></script>
	<script src="js/index.js"></script>
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
		
		if(isset($_GET["search"])){
			$search = $_GET["search"];
			$query = 'SELECT * FROM Movies WHERE name LIKE \'%'.$search.'%\' ORDER BY '.$order;
		}
		else{
			$query = 'SELECT * FROM Movies ORDER BY '.$order;
		}
		
		if(isset($_GET["edit"])){
			$edit = $_GET["edit"];
		}
		else{
			$edit = "false";
		}
	?>
</head>
<body>
	<div>
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
				<a class="topMenu" onclick="openLink('add')"><button>Add Movie</button></a>
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
		<form class="topMenu" id="searchForm" method="get" action="./">
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
		?>
			<input type="search" name="search" placeholder="Search...">
		</form>
	</div>
<?php
	if(isset($_GET["search"])){
?>
	<div>
		<p class="searchData">Displaying results for "<?php echo $search ?>" <form class="searchData" method="get" action="./">
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
		?>
			<input type="image" src="images/delete.png" width="15" alt="Clear search" />
		</form>
		</p>
	</div>
<?php
	}
?>
	<p>&nbsp;</p>
	<div>
		<div class="movie_box">
			<table>
				<tr>
					<td>
						<td class="posters"><img border="0" src="images/white.jpg" alt="Poster" width="100" height="148.148148148" /></td>
					</td>
					<td>
							<p><a href=".">Test Movie</a> (2014)</p>
							<p><span class="rating_span">PG-13</span> 120 mins</p>
							<p>Action, Comedy, Documentary</p>
							<p><a href="."><button>&#9658; 1080p HD</button></a></p>
					</td>
				</tr>
			</table>
		</div>
		<div class="movie_box">
			<table>
				<tr>
					<td>
						<td class="posters"><img border="0" src="images/white.jpg" alt="Poster" width="100" height="148.148148148" /></td>
					</td>
					<td>
							<p><a href=".">Test Movie</a> (2014)</p>
							<p><span class="rating_span">PG-13</span> 120 mins</p>
							<p>Action, Comedy, Documentary</p>
							<p><a href="."><button>&#9658; 1080p HD</button></a></p>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div>
	<table cellpadding="0" cellspacing="0" class="db-table">
		<!--<tr>
			<th id="name_header">
				<form method="get" action="./">
					<input type="hidden" name="order" value="name">
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
					<input id="name_button" type="submit" class="header" value="Name">
				</form>
			</th>
			<th>
				<form method="get" action="./">
					<input type="hidden" name="order" value="year">
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
					<input type="submit" class="header" value="Year">
				</form>
			</th>
			<th>
				<form method="get" action="./">
					<input type="hidden" name="order" value="time">
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
					<input type="submit" class="header" value="Duration">
				</form>
			</th>
			<th>
				<form method="get" action="./">
					<input type="hidden" name="order" value="genre">
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
					<input type="submit" class="header" value="Genre">
				</form>
			</th>
			<th>
				<form method="get" action="./">
					<input type="hidden" name="order" value="rating">
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
					<input type="submit" class="header" value="Rating">
				</form>
			</th>
			<th>
				<form method="get" action="./">
					<input type="hidden" name="order" value="quality">
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
					<input type="submit" class="header" value="Quality">
				</form>
			</th>
			<?php
				if ($edit == "true") {
			?>
					<th></th>
					<th></th>
			<?php
				}
			?>
			<th></th>
		</tr>-->
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
				
				if($count_play > 0){
		?>
				<tr>
					<td class="posters"><a href="<?php echo $movie_link ?>"><img id="<?php echo $id; ?>" border="0" src="images/white.jpg" alt="Poster" width="40.5" height="60" /></a></td>
		<?php
				}
				else{
		?>
					<td class="posters"><img id="<?php echo $id; ?>" border="0" src="images/white.jpg" alt="Poster" width="40.5" height="60" /></td>
		<?php
				}
		?>
					<td>
						<p><a href="<?php echo $link ?>"><?php echo $name ?></a> (<?php echo $year ?>)</p>
						<p><span class="rating_span"><?php echo $rating ?></span> <?php echo $time ?> - <?php echo $genre ?></p>
						<p>
					<?php
						if($count_play > 0){
					?>
							<a href="<?php echo $movie_link ?>"><button>&#9658; <?php echo $quality ?></button></a>
					<?php
						}
					?>
						</p>
					</td>
					<?php
						if ($edit == "true") {
					?>
							<td>
								<form method="post" action="edit">
									<input type="hidden" name="id" value="<?php echo $id ?>">
									<input type="image" class="formButton" title="Edit" src="images/edit.png">
								</form>
							</td>
							<td>
								<form method="post" action="scripts/delete">
									<input type="hidden" name="id" value="<?php echo $id ?>">
									<input type="image" class="formButton" title="Delete" src="images/delete.png">
								</form>
							</td>
					<?php
						}
					?>
				</tr>
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
	</table>
	</div>
	<div align="center">
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
				<p class="footerInfo"><?php echo exec("git describe"); ?> (<?php echo $branch; ?>) 
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
						<button onclick="openLink('restoredb')">Restore</button>
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
