This project was created in response to a programming exercise with the following requirements:

Create a user registration form that creates new records in a MySQL database. The user record should consist of name, email, password (with repeat check), city/state/zip, and a biography. Latitude and longitude should be looked up by city/state/zip and stored as well (via web service call). All fields are required and should be validated properly.

I chose to do the project in PHP and Laravel 4, which I have/had been working in a great deal at the time.


To install, please follow these steps:

1) "git clone" the repo to the appropriate place in your webserver scripts/files path. Configure your webserver to allow you to hit the project's public folder.  Also, you must have PHP 5.3+ installed (i.e. min Laravel 4 requirements).

2) Run "composer install" in the root directory of the project to fetch dependencies and build autoload files.

3) Set up a "blank" MySQL database, create an "apiuser" with r/w permissions to that db, and edit config/database.php to match the database and credentials. 

4) From the project root directory, run: php artisan migrate

5) From the project root directory, run: php artisan db:seed

6) Grab a cup-o-joe and enjoy registering users at: http://url-to-project-public-folder/


Attributions (aka credit where it's due):

I did not write the HttpStatusCodes class; see class docs for attribution. The project also depends on YUI CSS and jQuery (via cdn), and the entire project leverages Laravel 4.