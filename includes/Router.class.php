<?php

/**
* Router class
* Use it outter instance like : Router::auto();
* It's work by aliasing : /my/url will ve associated to a controler file OR a
*
*/
class Router {

    public static function auto($defaultPath)
    {
        // get route
        if (
            ($URL = self::coreGetRoute(['module','function','args'])) === false
        ){
            header('location:'.$defaultPath);
            exit;
        }

        // get alias and include controler (or other content)
        $alias = RouterManager::get($URL['module']);

        if (file_exists('controler/'.$alias)) {
            include 'controler/'.$alias;
        }
        elseif(file_exists('view/cache/'.$alias)) {
            include 'view/cache/'.$alias;
        }
    }

    /**
    * if isset $_GET
    * Return route array from $_GET[0]
    *
    * @param array : data structure array[]
    * ex : ( ['page','function', 'args'] )
    *
    * @return array
    * ex for : 'foo/bar/camel/case' will return :
    * array
    *   ['path'] = foo/bar/camel/case
    *   ['docroot'] = ./../../../
    *   ['page'] = 'foo'
    *   ['function'] = 'bar'
    *   ['args'] = [ 'camel' , 'case' ]
    */

    public static function coreGetRoute( $struct=['module','funct','args'] )
    {
        if (isset($_GET) && !empty($_GET)) {

            //  get path in array $parts
            $out=[];
            $out['path']=array_keys($_GET)[0];
            $parts=explode('/',$out['path']);

            // create docroot ressource
            // to create HTML backs links easier
            $out['docRoot']='./';
            for ($i=1; $i <= count($parts); $i++) {
                $out['docRoot'].='../';
            }

            // organise args
            $keyargs = array_pop($struct);
            foreach ($struct as $elem){
                $out[$elem] = array_shift($parts);
            }
            $out[$keyargs]=$parts;

            return $out;
        }
        return false;
    }
}

/**
* This manager is used to save or load a route
*/
class RouterManager {

    /**
    *  Get
    * @param : url getted
    * @return : targeted file or class
    */
    final public static function get($url){
        $DATA = parse_ini_file('routes.ini');

        if (isset($DATA[$url])) {
            return $DATA[$url];
        }
        return false;
    }

    /**
    * Set
    * @param : array [ url => 'alias' ]
    * @return : bool
    */
    final public static function set($url,$alias)
    {
        if (
            preg_match('#([a-z0-9-_.]{3,}/?)+#i',$url) &&
            preg_match('#([a-z0-9-_.]{3,}/?)+#i',$alias) &&
            (file_exists($alias) || class_exists($alias))
        ){
            // extract saved routes
            $DATA = parse_ini_file('routes.ini');

            // remove url if no alias
            if ( empty($alias) ) {
                unset($DATA[$url]);
            }

            // or merge new url
            else {
                $DATA = array_merge($DATA,$opts);
            }

            // saving routes
            $str='';
            foreach ( $DATA as $url => $alias ){
                $str.= "$url = '$alias'\n";
            }
            file_put_contents('routes.ini',$str);
            return true;
        }
        return false;
    }
}
