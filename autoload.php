<?php

define('BASE_PATH', realpath(dirname(__FILE__)));

// The vendor autoloading file
if (!file_exists(BASE_PATH . '/vendor/autoload.php')) {
    die('Please run `composer install` to generate your vendor directory');
}
require_once BASE_PATH . '/vendor/autoload.php';
require_once BASE_PATH . '/config.php';