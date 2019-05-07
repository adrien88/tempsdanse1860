<?php

/**
*  To debug
*/
ini_set('display_errors','1');

/**
*   Load config
*/
$CONFIG = parse_ini_file('config.ini',1);

/**
*   Class autoloader
*/
spl_autoload_register(function ($class) {
    include 'includes/' . $class . '.class.php';
});

/**
*   Defining route
*   At this point : program will be executed inner router
*/
Router::auto();
