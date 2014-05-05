Movie DB
========
The private movie streaming service

Build your own movie streaming service to stream your movies from anywhere in the world.

Requirements
------------

- Apache 2.2+
- MySQL 5.5+
- PHP 5+
- Git (optional)

Installation
------------

1. Clone this repository into your Apache root directory.

	# git clone git@bitbucket.org:virajchitnis/movie-db.git

2. Create a MySQL database to store your movie info in.

	# mysql -u root -p
	mysql> create database movies;

3. Create a MySQL user and password with privileges for this database only.

	mysql> grant all on movies.* to movies@localhost identified by 'some_password';
	mysql> flush privileges;
	mysql> exit

4. Copy the 'config.sample.php' file in the config directory to config.php and edit the variables within it to match the database details you setup in the earlier step.

	# cp config/config.sample.php config/config.php
	# nano config/config.php

5. Navigate to http://${your_server_ip}/movie-db to use it.
6. To install future updates, simply click on the 'Update' button at the bottom of the main page.
7. I would recommend that you password protect the movie-db folder if you wish to make it available over the internet.

Support
-------

If you come across any bugs or have a feature request, please add an issue to this project and I will try to fix it as time permits (I am a student).
