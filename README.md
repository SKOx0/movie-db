Movie Database
==============

Database to keep track of your movie collection

I have a massive movie collection gathered over time occupying hundreds of GBs of data on my NAS. A while back, I faced a major problem. Often times I wouldnt know whether or not a movie I or a friend wanted to watch was in my collection, and checking if it was there became too cumbersome. That is when I decided to build this web application to keep track of all the movies that I have in my collection and to view basic details about them.

I believe that I am not the only one who faces this problem, that is why I have made the code for my movie database opensource so that other people with the same problem can use it to keep track of their movies too.

If you wish to install this web application, you simply need a server or computer with a LAMP (Linux, Apache, MySQL, PHP) server.

Installation
============

Requirements - 
  - LAMP Server
  - Git

Proceedure -
  - Clone this repository into your Apache root directory.
  - Create a MySQL database to store your movie info in.
  - Create a MySQL user and password with privileges for this database only.
  - Use the database.mysql file in the config directory to create tables in your database.
  - Copy the 'config.sample.php' file in the config directory to config.php and edit the variables within it to suit your needs.
  - Navigate to ${yourlampip}/movie-db to use it.
  - To install future updates, simply click on the 'Update' button at the bottom of the main page.
  - I would recommend that you password protect the movie-db folder if you wish to make it available over the internet.

If you come across any bugs please add an issue to this project and I will try to fix it as time permits (I am a student).
