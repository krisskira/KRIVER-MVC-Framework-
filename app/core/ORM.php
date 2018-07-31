<?php
abstract class ORM extends Database{
    protected static $table;
    protected static $columns = array();
    protected static $alias_columns = array();
    protected static $isMultidimensional = FALSE;
    private static $access_to_properties = false;
    
    function __construct(){
        parent::__constructor();
        // Array positions
        $typeOf     = 0;
        $nullable   = 1;
        
        if( !isset( $this::$table ) ){
            $errMsg   = "The table name is undefined in entity ".get_class ($this);    
            $errorTrace = self::prepareError(debug_backtrace(), $errMsg);
            trigger_error($errorTrace ,E_USER_ERROR);
        }

        if( !isset( $this::$alias_columns ) ){
            $this::$alias_columns = array();
        }

        foreach ($this::$columns as $field => $properies){   

            $fieldProperies = explode( "," , $properies);
           
            // Verifica que cumpla con los tipos de datos
            switch($fieldProperies[$typeOf])
            {
                case "string":
                case "int":
                case "bool":
                    break;
                default:
                    throw new Exepction ( "Invalid Property ({$$field})" );
            }

            // Verifica que cunmpla con los valores para tipos nulos o requeridos
            switch($fieldProperies[$nullable])
            {
                case "required":
                case "null":
                    break;
                default:
                throw new Exepction ( "Invalid Property ({$$field})" );
            }

            $this::$columns[$field] = array( "typeof"    =>  $fieldProperies[$typeOf],
                                                "nullable"  =>  $fieldProperies[$nullable],
                                                "value"     =>  "");
        }       
    }

    /**
     * Prepare the setters
     */

    function __set($field, $value)
    {
        if( !self::$access_to_properties ){
            $errMsg   = "the property $field in the entity " .get_class ($this). " is only accessible using the suffix set.";    
            $errorTrace = self::prepareError(debug_backtrace(), $errMsg);
            trigger_error($errorTrace ,E_USER_ERROR);
        }
        self::$access_to_properties = false;

        // Verifica si la propiedad existe en el array de propiedades
        // si no existe verifica si la propiedad no existe en el array
        // de alias
        if( !array_key_exists( $field , $this::$columns ) ){

            if( !array_key_exists( $field , $this::$alias_columns ) ){
                $errMsg   = "The field $field is undefined in entity ".get_class ($this)."";    
                $errorTrace = self::prepareError(debug_backtrace(), $errMsg);
                trigger_error($errorTrace ,E_USER_ERROR);
            }
            else {
                // Busca el nombre de la propiedad en el array de alias y lo establece en
                // la variable de $field
                $field = $this::$alias_columns[$field];
            }
        }

        if( is_null($value) ){
            $errMsg   = "The params are required in the method, the value null is invalid.";    
            $errorTrace = self::prepareError(debug_backtrace(), $errMsg);
            trigger_error($errorTrace ,E_USER_ERROR);
        }

        if( $this::$columns[$field]["nullable"] == "required" )
        {
            if( is_null( $value ) || empty( $value ) ){
                $errMsg = "The field $field is  required in ".get_class ($this);
                $errorTrace = self::prepareError(debug_backtrace(), $errMsg);
                trigger_error($errorTrace ,E_USER_ERROR);
            }
        }

        if( gettype($value) !=  $this::$columns[$field]["typeof"] )
        {
            $errMsg = "The value (".gettype($value).") is not match with (".$this::$columns[$field]["typeof"].") in (".get_class ($this).")";
            $errorTrace = self::prepareError(debug_backtrace(), $errMsg);
            trigger_error($errorTrace ,E_USER_ERROR);
        }

        $this::$columns[$field] = $value ;
    }

    /**
     * Prepare the getters
     */

    function __get($field){

        if( !self::$access_to_properties ){
            $errMsg   = "the property $field in the entity " .get_class ($this). " is only accessible using the suffix get.";    
            $errorTrace = self::prepareError(debug_backtrace(), $errMsg);
            trigger_error($errorTrace ,E_USER_ERROR);
        }
        self::$access_to_properties = false;

        // Verifica si la propiedad existe en el array de propiedades
        // si no existe verifica si la propiedad no existe en el array
        // de alias
        if( !array_key_exists( $field , $this::$columns ) ){

            if( !array_key_exists( $field , $this::$alias_columns ) ){
                $errMsg   = "The field $field is undefined in entity ".get_class ($this)."";    
                $errorTrace = self::prepareError(debug_backtrace(), $errMsg);
                trigger_error($errorTrace ,E_USER_ERROR);
            }
            else {
                // Busca el nombre de la propiedad en el array de alias y lo establece en
                // la variable de $field
                $field = $this::$alias_columns[$field];
            }
        }

        return is_array( $this::$columns[$field] )?"":$this::$columns[$field];
    }

    /**
     * Process properties and method
     */

    public function __call($func, $value){

        $funcPrefix = substr($func,0,3);
        $propertie  = substr($func,3,strlen($func)-3);
    
        if ( !$func ){
            $errMsg = "The method {$func} is not found in the entity ".get_class ($this);
            $errorTrace = self::prepareError(debug_backtrace(), $errMsg);
            trigger_error($errorTrace ,E_USER_ERROR);
        }

        switch( $funcPrefix ){
            case "set":
                self::$access_to_properties = true;
                if( count($value) > 1 && count($value) <= 0 ){
                    $errMsg = "The method {$func} the entity ".get_class ($this)." only accept one parameter.";
                    $errorTrace = prepareError(debug_backtrace(), $errMsg);
                    trigger_error($errorTrace ,E_USER_ERROR);
                }
        
                $value = $value[0];
                self::__set( $propertie, $value );
                break;

            case "get":
                self::$access_to_properties = true;
                return self::__get( $propertie );
                break;
            default:
                self::$access_to_properties = false;
                $errMsg = "The method {$func} is not found in the entity ".get_class ($this);
                $errorTrace = self::prepareError(debug_backtrace(), $errMsg);
                trigger_error($errorTrace ,E_USER_ERROR);
                break;
        }

    }

     /**
     * Preformatter error
     */

    private static function prepareError($trace,$message){
        $error["file"]      = $trace[1]["file"];
        $error["line"]      = $trace[1]["line"];
        $error["type"]      = $trace[1]["class"];
        $error["message"]   = $message;
        return serialize($error);
    }

    public function getProperties () {

        $func_reserve = ["","__construct","__set","__get","__call","prepareError"];

        $ret = array (
            "properties"    => array(),
            "method"        => array()
        );

        foreach (static::$columns as $field => $properies){
            array_push( $ret["properties"], $field );
        }

        foreach (static::$alias_columns as $field => $properies){
            array_push( $ret["properties"], $field );
        }

        foreach ( get_class_methods( get_class($this) ) as $func) {
            if( !array_search( $func,$func_reserve,TRUE ) ) {
                array_push( $ret["method"], $func );
            }
        }
        return $ret;
    }


}