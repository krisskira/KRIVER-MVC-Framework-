<?php 
/** 
 *   KRIVER DEVICE CONFIDENTIAL
 *  ____________________________
 * 
 * Copyright (c) 2018 KRIVER DEVICE
 * All Rights Reserved
 * 
 * This product is protected by copyright and distributed under
 * licenses restricting copying, distribution, and decompilation.
 * 
 * ** NOTICE:
 * ===============
 *  Revise las condiciones de uso y licencias en el archivo
 *  LEAME.txt
 * 
 * ** Contributors:
 * ================
 *      Autor:       Crhstian David Vergara Gomez
 *      Version:     1.0
 *      Description: Initial API and implementation
 * ========================================================================
 * 
 *      Name:           
 *      Description:    
 * 
 * **/
?>

<?php
define ("_DIR_CONFIG_","application/config/");
define ("_DIR_CONTROLLER_","application/controller/");
define ("_DIR_ORM_","application/orm/");
define ("_DIR_VIEW_","application/view/");
define ("_DIR_JAVASCRIPT_","resources/js/");
define ("_DIR_CSS_","resources/styles/");
define ("_DIR_IMAGES_","resources/img/");

$requestUri  = explode ( "/", $_SERVER["REQUEST_URI"] );    // Directorio base
define ("_BASE_URL_", $_SERVER["REQUEST_SCHEME"]."://".$_SERVER["HTTP_HOST"]."/".$requestUri[$pos_base_url]."/");
unset ($requestUri);

include "Application/config/Routes.php";
$route["core"]          = "core/";
$route["controller"]    = "application/controller/";

abstract class Router {

    private static $route = array("path"=>"","class"=>"","func"=>"","params"=>array());

    static function getRoute(){
        global $route;
        $class      = $route["controller_default"];
        $classPath  = $route["controller"] . $route["controller_default"].".php";
        $func       = "index";
        $params     = array();

        $isFound     = false;

        // SI existe este key en la estructura verifica si existe 
        // la ruta y/o la funcion
        $uri = (isset($_SERVER["PATH_INFO"]))?$_SERVER["PATH_INFO"]:false;
        
        if( $uri ) {

            // Elimina el "/" que se encuentra al inicio de la uri
            $uri = explode ("/", $uri );
            unset($uri[0]);
            $uri = implode("/", $uri);

            // Verifica si existe el controlador en la carpeta indicada en $uri.
            if ( file_exists ( $route["controller"] . $uri . ".php" ) ) {
                $classPath  = $route["controller"] . $uri . ".php";
                $class      = explode ("/", $uri );
                $class      = $class[count( $class )-1];
                $isFound    = true;
            } 
            else { 
            // Verifica si existe el controlado en la carpeta indicada en $uri
            // y si esta invocando un metodo en la uri
            $findFuncPath   = explode("/",$uri);
            $tempPath       = explode("/",$uri);

                foreach ( $findFuncPath as $index=>$file ) {

                    $currPath = $route["controller"] . implode ("/", $tempPath) . ".php";
                    
                    if ( file_exists ( $currPath ) ) {

                        $classPath  = $currPath;
                        $class      = $tempPath[count($tempPath)-1];
                        $data       = array_values( array_diff( $findFuncPath, $tempPath ) );
                        $func       = ( $data[0]!="" )?$data[0]:$func;
                        unset($data[0]);
                        $params     = array_values( $data );
                        $isFound    = true;
                        break;
                    }
                    else {
                        unset($tempPath[count($tempPath)-1]);
                    }
                } // Fin de ForEach
            } // Fin del If
        }
        else {
            // Si no existe el key "Path_Info" entonces se esta Accediendo al index
            $isFound = true;
        } // Fin frl If

        // Si no se encuentra el fichero en los directorios entonces 
        // puede que se trate de un alias en las rutas, se realizara la
        // busqueda en el array de rutas
        if( !$isFound ) {

            $uri = (isset($_SERVER["PATH_INFO"]))?explode("/", $_SERVER["PATH_INFO"]):false;

            // Recorre el array de rutas
            foreach($route as $thisUri=>$thisPath){

                $uri_Is_Diff    = false;
                $indexChunkUri  = 0;
                $currUriArray   = explode( "/",$thisUri );

                // echo "Comparando: {$thisUri} => ".implode('/',$uri)."<br>";
                // Compara las partes que conforman las uri en busca de 
                // diferencias si las encuentra rompe el ciclo y establece 
                // el flag $uri_Is_Diff a true, si no las encuentra significa
                // que ha encontrado la uri solicitada en el array de rutas
                foreach($currUriArray as $chunkUri){ 
                    
                    if($chunkUri != $uri[$indexChunkUri]){
                        $uri_Is_Diff = true;
                        break;
                    }
                    $indexChunkUri++;
                }
                // Si no encontro la uri en el array instancia al controllador por defecto
                // y le pasa por parametros al metodo index un array con los datos de la 
                // uri solicitada
                if( $uri_Is_Diff ){
                    // Elimina el "/" que se encuentra al inicio de la uri
                    $params = $uri ;
                    unset($params[0]);
                    $params = array_values( $params );
                    if( trim($params[count($params)-1])=="" ) unset( $params[count($params)-1] );
                } 
                else {
                    // Si encuentra la uri en el array de rutas verifica si esta instanciando
                    // solo la clase o si se invoca un metodo y se le pasan parametros
                    if ( file_exists ($route["controller"].$thisPath.".php") ) {
                        $classPath  = $thisPath;
                        $params     = array_values( array_diff( $uri, $currUriArray ) );
                    }
                    else {
                        // Si no existe el archivo del controlador puede ser que se haya definido
                        // un nombre de funcion en la urio
                        $findFuncPath = explode("/",$thisPath);

                        // La funcion es la ultima parte de la URI
                        $func = $findFuncPath[count( $findFuncPath ) -1];
                        unset( $findFuncPath[count( $findFuncPath ) -1] );
                        
                        // el nombre del controlador es la antepenultima posicion de la URI
                        $class      = $findFuncPath[count( $findFuncPath ) -1];
                        $classPath  = $route["controller"] . implode( "/",$findFuncPath ) . ".php";
                        $params     = array_values( array_diff( $uri, $currUriArray ) );

                        if ( !file_exists ( $classPath ) ) {
                           $classPath   =  $route["controller"] . $route["controller_error"] . ".php";
                           $class       =  explode("/",$route["controller_error"]);
                           $class       =  $class[count($class)-1];
                           $func        =  "error";
                           $params      =  array("message"=>"<b/>Error:</b> File declared in <i>Routes Controller</i> No Found.");
                        }
                    }
                    break;
                }
            }   
        }
        self::$route["path"]    =  $classPath;
        self::$route["class"]   =  $class;
        self::$route["func"]    =  $func;
        self::$route["params"]  =  $params;
        return self::$route;
    }
}