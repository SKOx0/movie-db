<?php
	exec("/usr/bin/git pull; /usr/bin/git fetch --tags");
	header('Location: ../');
?>