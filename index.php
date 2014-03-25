<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Movie Collection</title>
	<link rel="stylesheet" href="css/index.css">
	<script src="js/openlink.js"></script>
	<?php
		function table_exists ($conn, $tablename) {
			$res = mysqli_query($conn,"SHOW TABLES LIKE '".$tablename."'");
			if($res) {
				return true;
			}
			return false;
		}
	
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
			if ($edit == "true") {
		?>
				<a class="topMenu" onclick="openLink('add.php')"><button>Add Movie</button></a>
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
		<!--<a class="searchData" href="./"><button>Clear search</button></a>-->
		<!--<form class="searchData" method="get" action="./">
			<input type="image" class="formButton" title="Clear search" src="images/delete.png">
		</form>-->
	</div>
<?php
	}
?>
	<div>
	<table cellpadding="0" cellspacing="0" class="db-table">
		<tr>
			<th></th>
			<th>
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
					<input type="submit" class="header" value="Name">
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
		</tr>
		<?php
			include 'config/config.php';
			$connection = mysql_connect($HOSTNAME,$USERNAME,$PASSWORD) or die('Connection failed!');
			mysql_select_db($DATABASE,$connection) or die('Database select failed!');
			
			if (!table_exists($connection, "Movies")) {
				exec("/usr/bin/mysqldump -u ".$USERNAME." -p".$PASSWORD." movies < config/database.sql");
			}
			
			$result = mysql_query($query,$connection) or die('Select failed!');
			
			$numrows = mysql_numrows($result);
			for($i = 0; $i < $numrows; $i++){
				$id = mysql_result($result,$i,'id');
				$poster = mysql_result($result,$i,'poster');
				$name = mysql_result($result,$i,'name');
				$year = mysql_result($result,$i,'year');
				$time = mysql_result($result,$i,'time');
				$genre = mysql_result($result,$i,'genre');
				$rating = mysql_result($result,$i,'rating');
				$quality = mysql_result($result,$i,'quality');
				$link = mysql_result($result,$i,'link');
				
				$count_play;
				if (file_exists("movies")) {
					$query_play = 'SELECT count(id) FROM Files WHERE id=\''.$id.'\'';
					$result_play = mysql_query($query_play,$connection) or die('Select failed!');
					$count_play = mysql_result($result_play,0,'count(id)');
				}
				else {
					$count_play = 0;
				}
				
				if($count_play > 0){
		?>
				<tr>
					<td><a href="play.php?id=<?php echo $id ?>"><img border="0" src="<?php echo $poster ?>" alt="Poster" width="40.5" height="60" /></a></td>
		<?php
				}
				else{
		?>
					<td><img border="0" src="<?php echo $poster ?>" alt="Poster" width="40.5" height="60" /></td>
		<?php
				}
		?>
					<td><a href="<?php echo $link ?>"><?php echo $name ?></a></td>
					<td><?php echo $year ?></td>
					<td><?php echo $time ?></td>
					<td><?php echo $genre ?></td>
					<td><?php echo $rating ?></td>
					<td><?php echo $quality ?></td>
					<?php
						if ($edit == "true") {
					?>
							<td>
								<form method="post" action="edit.php">
									<input type="hidden" name="id" value="<?php echo $id ?>">
									<input type="image" class="formButton" title="Edit" src="images/edit.png">
								</form>
							</td>
							<td>
								<form method="post" action="scripts/delete.php">
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
			$query = 'SELECT count(id) FROM Movies';
			$result = mysql_query($query,$connection) or die('Select failed!');
			$count = mysql_result($result,0,'count(id)');
			mysql_close($connection);
		?>
	</table>
	</div>
	<div align="center">
		<p><?php echo $count ?> Movies</p>
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
				<p><?php echo exec("git describe"); ?> (<?php echo $branch; ?>) 
				<?php
					if ($edit == "true") {
						if (file_exists('movies.sql')) {
							exec("rm movies.sql");
						}
						if (file_exists('moviedb.mbk')) {
							exec("rm moviedb.mbk");
						}
						include('config/config.php');
						exec("/usr/bin/mysqldump -u ".$USERNAME." -p".$PASSWORD." movies > movies.sql");
						exec("tar -cf moviedb posters movies.sql");
						exec("gzip -S .mbk moviedb");
				?>
						<button onclick="openLink('scripts/gitupdate.php')">Update</button> 
						<button onclick="openLink('moviedb.mbk')">Backup</button>
						<button onclick="openLink('restore.php')">Restore</button>
				<?php
					}
				?>
				</p>
		<?php
			}
		?>
		<p id="copyright">&copy; 2013-<script>document.write(new Date().getFullYear());</script> Viraj Chitnis</p>
	</div>
</body>
</html>
