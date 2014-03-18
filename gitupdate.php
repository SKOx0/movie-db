<?php
	exec("git pull; git fetch --tags");
	header('Location: ./');
?>