# Requirements:
* PHP 7.0+
* Apache
* MySql
* Composer
* Git

# Instalation
* If you don't have apache and mysql installed, install xampp https://www.apachefriends.org/ro/index.html
* If you don't have GIT installed you cand download it from here: https://git-scm.com/downloads
* Open a terminal/comand prompt and cd to <path_to_xampp>/htdocs
* Run git clone https://github.com/alexcicioc/svcourse2018.1.git
* Download and install composer:
https://getcomposer.org/download/
* In the terminal run: cd svcourse2018.1
* In the terminal run: composer install
* Create a new mysql database (you can use phpmyadmin http://localhost/phpmyadmin) and import the sql file(s) from https://github.com/alexcicioc/svcourse2018.1/tree/master/sql/tables
* Copy config.php.example and paste it in the same directory with the name config.php
* Change the database configuration from config.php to match your database server configuration
* Enable apache's mod_rewrite if not already enabled: https://stackoverflow.com/questions/12272731/using-mod-rewrite-with-xampp-and-windows-7-64-bit
* Access http://localhost/svcourse2018.1 to check if everything is ok
