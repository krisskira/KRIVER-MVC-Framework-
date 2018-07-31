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
require_once $route["controller"].$route["controller_error"].".php";
/**
*    Handler de Notificaciones de Warning y Errores
**/
register_shutdown_function( "fatal_handler" );
function fatal_handler(){
    global $route;
    $error = error_get_last();
    if (isset($error)){
        $class = explode("/",$route["controller_error"]);
        $class[count($class)-1]::error($error);
    }
}

/**
*    Handler de Rutas para assets
**/
function base_url( $filePath = "", $asset = "none", $ext="png", $guard = false ) {

    switch ($asset) {
        case "image":
            $path = _BASE_URL_._DIR_IMAGES_.$filePath.".".$ext;
            break;
        case "css":
            $path = _BASE_URL_._DIR_CSS_.$filePath."css";
            break;
        case "js":
            $path = _BASE_URL_._DIR_JAVASCRIPT_.$filePath."js";
            break;
        default:
            $path = _BASE_URL_.$filePath;
    }

    if( $filePath !="" && $guard ){
        if( !file_exists($path) ) {
            $path = "File No Found"; 
        }
    }
    return $path;                
}
  