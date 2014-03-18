<?php
	exec("cd ..; sudo git pull; sudo git fetch --tags");
	header('Location: ../');
?>