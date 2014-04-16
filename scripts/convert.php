<?php
	if ((isset($_POST['quality'])) && (isset($_POST['filename'])) (isset($_POST['email']))) {
		$quality = $_POST['quality'];
		$filename = $_POST['filename'];
		$email = $_POST['email'];
		
		echo $quality."<br>";
		echo $filename."<br>";
		echo $email."<br>";
	}
?>