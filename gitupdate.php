#!/usr/bin/php

<?php
	exec("git pull; git fetch --tags");
	header('Location: ./');
?>