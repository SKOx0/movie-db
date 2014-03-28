<!DOCTYPE html>
<html>
	<head>
		<code><script src="mediaelement/jquery.js"></script>
		<script src="mediaelement/mediaelement-and-player.min.js"></script>
		<link rel="stylesheet" href="mediaelement/mediaelementplayer.css" /></code>
		<title>Watch Movies</title>
		<?php
			$link;
			$mod_link;
			if(isset($_GET["id"])){
				$id = $_GET["id"];

				include 'config/config.php';
				$connection = mysql_connect($HOSTNAME,$USERNAME,$PASSWORD) or die('Connection failed!');
				mysql_select_db($DATABASE,$connection) or die('Database select failed!');

				$query = 'SELECT quality FROM Movies WHERE id=\''.$id.'\'';
				$result = mysql_query($query,$connection) or die('Select failed!');
				$quality = mysql_result($result,0,'quality');

				$query = 'SELECT file_name FROM Files WHERE id=\''.$id.'\'';
				$result = mysql_query($query,$connection) or die('Select failed!');
				$file_name = mysql_result($result,0,'file_name');

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
		<div>
			<video src="<?php echo $mod_link ?>" width="320" height="240"></video>
		</div>
		
		<script>
			// using jQuery
			$('video,audio').mediaelementplayer({
				// if the <video width> is not specified, this is the default
				defaultVideoWidth: 480,
				// if the <video height> is not specified, this is the default
				defaultVideoHeight: 270,
				// if set, overrides <video width>
				videoWidth: -1,
				// if set, overrides <video height>
				videoHeight: -1,
				// width of audio player
				audioWidth: 400,
				// height of audio player
				audioHeight: 30,
				// initial volume when the player starts
				startVolume: 0.8,
				// useful for <audio> player loops
				loop: false,
				// enables Flash and Silverlight to resize to content size
				enableAutosize: true,
				// the order of controls you want on the control bar (and other plugins below)
				features: ['playpause','progress','current','duration','tracks','volume','fullscreen'],
				// Hide controls when playing and mouse is not over the video
				alwaysShowControls: false,
				// force iPad's native controls
				iPadUseNativeControls: false,
				// force iPhone's native controls
				iPhoneUseNativeControls: false, 
				// force Android's native controls
				AndroidUseNativeControls: false,
				// forces the hour marker (##:00:00)
				alwaysShowHours: false,
				// show framecount in timecode (##:00:00:00)
				showTimecodeFrameCount: false,
				// used when showTimecodeFrameCount is set to true
				framesPerSecond: 25,
				// turns keyboard support on and off for this instance
				enableKeyboard: true,
				// when this player starts, it will pause other players
				pauseOtherPlayers: true,
				// array of keyboard commands
				keyActions: []
			});
		</script>
	</body>
</html>
