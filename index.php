<?php

ini_set('display_errors','1');

/**
*   Load config
*/
$SITE = parse_ini_file('config.ini',1)['site'];
$DBSITE = parse_ini_file('config.ini',1)['database'];

/**
*   Class autoloader
*/
spl_autoload_register(function ($class) {
    include 'includes/' . $class . '.class.php';
});


/**
*   Defining route
*/
Router::auto($SITE['landing']);
