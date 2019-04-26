<?php

switch($URL['function']) {
    // add une page
    case 'add':

    break;
    // edit a page
    case 'edit':

    break;
    // delete a page
    case 'del':

    break;

    // print a page
    default:
        if(
            ($cache = new cache($URL['function'])) &&
            (($PAGE = $cache->load()) === false)
        ){
            $content = "page controler<br>";
            $cache->save($content);
            echo $content;
        }
        else {
            echo $PAGE;
        }
    break;
}
