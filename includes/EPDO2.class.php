<?php

/**
 *  ePDO : extandedPDO
 *  To change config PDO : please, edit the config.ini file at root of website.
 *  Methods allow you to all basics functions
 */
class EPDO2 {

    // PDO instance
    private static $PDO = null;

    // basename
    private static $basename = '';

    // table locking
    private static $tablename = '';

    // get table elements
    private static $tableStruct = '';

    // get table name
    private static $tableRegex = '';

    /** __________________________________________________________________________________
    *   Construct :
    *   @param void
    *   @return success:object:EPDO
    *   @return error:false
    *
    */
    public function __construct($params = null){
        self::connectDB();
    }

    /** __________________________________________________________________________________
    *   Create instance to static using
    *   @param void
    *   @return void
    */
    final public static function connectDB()
    {
        if(is_null(self::$PDO)){
            // Load congig
            $DB = $params ?? Config::load('config.ini')['database'];

            // Init DB connect
            try {
                $req = 'mysql:dbname='.$DB['name'].';host='.$DB['host'].';';
                $options = [
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES ".$DB['charset']
                ];
                // instnce PDO in static var
                self::$PDO = new PDO ($req,$DB['login'],$DB['passwd'],$options);
                unset($DB);
            }
            catch (PDOException $e){
                // print error
                exit('Database connection error : '.$e);
            }
        }
    }

    /** __________________________________________________________________________________
    *   Associate to a table
    *   @param tablename:string
    *   @return tablename:string
    *
    */
    final public static function getTable($tablename = null)
    {
        if(isset($tablename)){
            self::$tablename = $tablename;
        }
        return self::$tablename;
    }

    /** __________________________________________________________________________________
    *   query a psecific patter data into table
    *   @param : string query
    *   @return success:array
    *   @return error:false:throw:error_message
    */
    final public static function search(array $pattern, $cols = '*') {
        $str = '';
        foreach($pattern as $colname => $patt){
            $str .= $colname.' LIKE '.$patt.' AND';
        }
        $req = 'SELECT '.$cols.' WHERE '.$str.';';
        return self::query($req);
    }


    //

    /** __________________________________________________________________________________
    *   test if table exists
    *   @param : tablename
    *   @return success:true
    *   @return error:false
    */
    final public static function ifTableExists($tablename) {
        $req = "SELECT TABLE_NAME  FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA='$basename';";
        $list = $this->query($req);
        if(in_array($tablename,$list)){
            return true;
        }
        else {
            return false;
        }
    }


    /** __________________________________________________________________________________
    *   query data into table
    *   @param : string query
    *   @return success:array
    *   @return error:false:throw:error_message
    *
    */
    final public static function query($req, $FETCH = null) {
        $stat = self::$PDO->prepare($req);
        $stat->execute();
        if(
            ($error = $stat->errorInfo()[2])
            && !empty($error)
        ){
            unset($stat);
            return $error;
        }
        else {
            if (!is_bool($stat)){
                $data = $stat->fetchAll($FETCH);
                unset($stat);
                if(!empty($data)){
                    if(count($data) > 1 ){
                        return $data;
                    }
                    else {
                        return $data[0];
                    }
                }
                // return isset($data[1]) ? $data : $data[0];
            }
            else{
                unset($stat);
                return false;
            }
        }
    }



    /** __________________________________________________________________________________
    *   Insert data into table
    *   @param : string tablename; array : data []
    *   @return success:true
    *   @return error:(string)errormessage
    *
    */
    final public static function insert(array $data,$table = null) {
        // formater la requête
        $prepreq = implode(',',array_keys($data));
        $prepval = ':'.implode(',:',array_keys($data));

        // Sand
        $req = "INSERT INTO ".self::getTable($table)." ($prepreq) VALUES ($prepval);";
        $stat = self::$PDO->prepare($req);
        $stat->execute($data);
        if(
            ($error = $stat->errorInfo()[2]) &&
            !empty($error)
        ) {
            return $error."\nreq: ".$req."\n\n";
        }
        else {
            return true;
        }
    }



    /** __________________________________________________________________________________
    *   Update data into table
    *   @param : string tablename; array : data []
    *   @return success:true
    *   @return error:(string)errormessage
    *
    */
    final static public function update(array $data = [], array $cond = [],$table = null) {

        $sdata = [];
        // write request
        $req = 'UPDATE '.self::getTable($table).' SET ';
        foreach ($data as $key => $value) {
            $req .= ''.$key.' = :'.$key.', ';
        }
        $req = substr($req,0,-2);

        $req.=' WHERE ';
        foreach ($cond as $key => $value) {
            $req .= ''.$key.' = '.self::$PDO->quote($value).' AND ';
        }
        $req = substr($req,0,-4).';';

        // Sand
        $stat = self::$PDO->prepare($req);
        $stat->execute($data);
        if(
            ($error = $stat->errorInfo()[2]) &&
            !empty($error)
        ){
            return $error."\nreq: ".$req."\n\n";
        }
        else {
            return true;
        }
    }


    /** __________________________________________________________________________________
    *   Delete data from table
    *   @param : string tablename; array : data []
    *   @return success:bool
    *   @return error:(string)errormessage
    *
    */
    final public static function delete(array $cond,$table = null) {
        $req = 'DELETE FROM '.self::getTable($table).' WHERE ';
        foreach ($cond as $key => $value) {
            $req .= ''.$key.' = :'.$key.' AND ';
        }
        $req = substr($req,0,-4).';';
        $stat = self::$PDO->prepare($req);
        $stat->execute($cond);
        if(
            ($error = $stat->errorInfo()[2])
            && !empty($error)
        ){
            return $error."\nreq: ".$req."\n\n";
        }
        else {
            return true;
        }
    }




}
