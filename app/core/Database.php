<?php
include _DIR_CONFIG_."application.php";
abstract class Database {
    protected static $conection = "";
    protected static $isMultidimensional = TRUE;
    
    function __constructor(){
        global $config;
        $connStr =  "{$config['database']['driver_database']}:".
        "host={$config['database']['host_database']};".
        "dbname={$config['database']['name_database']}";

        try{
            self::$conection = new PDO(
                $connStr,
                $config['database']['user_database'],
                $config['database']['password_database']
            );
        } catch (PDOException $e) {
            print "¡Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    
    }

    public function getTable($filters=[],$isMultidimensional=TRUE) {

        static::$isMultidimensional = $isMultidimensional;

        var_dump($filters);
       
        $tempRetColunms = array();
        $rows = self::$conection->prepare("SELECT * FROM ".static::$table);
        $rows->execute();

        if( $rows->rowCount() > 1 && !$isMultidimensional ){
            $errMsg = "The entity  ".get_class ($this)." return more of one result.";
            $errorTrace = self::prepareError(debug_backtrace(), $errMsg);
            trigger_error($errorTrace ,E_USER_ERROR);
        }

        foreach ($rows as $row){

            foreach($row as $field=>$value){

                if( array_key_exists($field,static::$columns) ){
                    static::$columns[$field]["value"] = $value;
                }
            }
            if( $isMultidimensional ){ $tempRetColunms[] = static::$columns; }
        }
    
        if( $isMultidimensional ){  static::$columns = $tempRetColunms; }

        echo "<pre>";
        var_dump (static::$columns);
        echo "<hr><pre>";

    }

    private static function prepareError($trace,$message){
        $error["file"]      = $trace[1]["file"];
        $error["line"]      = $trace[1]["line"];
        $error["type"]      = $trace[1]["class"];
        $error["message"]   = $message;
        return serialize($error);
    }
}