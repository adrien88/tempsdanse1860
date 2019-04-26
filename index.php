<?php

ini_set('display_errors','1');

/**
*   Load config
*/
$METASITE = parse_ini_file('config.ini',1)['site'];
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
if (!isset($_GET['page'])){
    header('location:?pages/');
    exit;
}
else {
    Router::auto();
}
