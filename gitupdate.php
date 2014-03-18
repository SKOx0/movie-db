<?php
	exec("sudo git pull; sudo git fetch --tags");
	header('Location: ./');
?>