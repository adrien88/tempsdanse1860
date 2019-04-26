<?php

/**
*   Load config
*/
$METASITE = parse_ini_file('config.ini')['site'];
$DBSITE = parse_ini_file('config.ini')['database'];

/**
*   Class autoloader
*/
spl_autoload_register(function ($class) {
    include 'includes/' . $class . '.class.php';
});


/**
*   Definng a rooute
*/
Router::auto();
