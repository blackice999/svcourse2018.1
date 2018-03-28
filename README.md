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

# Documentation:
The framework is a minimalistic [MVC](https://www.tutorialspoint.com/design_pattern/mvc_pattern.htm) REST API framework
## Structure:
* **specs** - contains api description in YAML format [OpenApi](https://github.com/OAI/OpenAPI-Specification/blob/master/versions/2.0.md) style
* **sql** - contains all the sql files needed to create your database
  * **tables** - contains table definitions
* **src** - contains all the source code
  * **Api** - contains all the API related code
    * **Controllers** - contains the controllers to handle the routes, Controller interface and the Router which redirects the request to it's specific controller
    * **Models** - contains business logic related classes, the ActiveRecord model (see [Active Record Pattern](https://en.wikipedia.org/wiki/Active_record_pattern)), database models
  * **Services** - folder that contains services, helper functions, drivers
    * **Authentication** - contains helper classes that handles generating authentication tokens and checking them
    * **Http** - contains helper classes to handle requests, responses and HTTP definitions (see more about the [Http Protocol](https://code.tutsplus.com/tutorials/http-the-protocol-every-web-developer-must-know-part-1--net-31177)
    * **Pesistence** - contains the persistence layer, basically drivers that know how to talk to a database or a caching system (see more about the [Active Record Pattern](https://stackoverflow.com/questions/16016023/what-is-the-use-of-a-persistence-layer-in-any-application))
