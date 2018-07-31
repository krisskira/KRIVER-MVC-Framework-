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
 error_reporting(0);                                                            // Oculta los errores fatales y las alertas

require_once ( "core/Vars.php" );                                               // Variables Generales del Framework
require_once ( "core/Router.php" );                                             // Rutas hacia los controladores y los modelos
require_once ( "core/Autoload.php" );                                           // Cargador de las Clases core y models
require_once ( "core/Lib.php" );                                                // Contiene las Funciones generales del framework
require_once _DIR_CONFIG_."application.php";

$classToLoad = Router::getRoute() ;                                             // Optine de forma automatica el controlador, 
                                                                                // el metodo y los parametros segun la ruta del la url

include $classToLoad["path"];                                                   // incluye el controlador solicitado

if( class_exists ( $classToLoad["class"] ) ){                                   // Verifica que la clase y el metodo a invocar exista
    $controller_class = new $classToLoad["class"]();                            // si no existe, instancia la clase y el metodo por defecto

    if( method_exists ( $controller_class, $classToLoad["func"] )){
        $params = $classToLoad["params"];
        eval( "\$controller_class->".$classToLoad["func"]."(\$params);");
    }
    else {
        include $route["controller"] . $route["controller_error"] . ".php";
        $controller_class =  explode("/",$route["controller_error"]);
        $controller_class =  $controller_class[count($controller_class)-1];
        $params           =  array("message"=>"<b/>Error:</b> The method: <i>{$classToLoad['func']}</i> No Found in <i>{$classToLoad['class']}</i>.");      
        $controller_class->error($params);
    }
} 
else {
        include $route["controller"] . $route["controller_error"] . ".php";
        $controller_class =  explode("/",$route["controller_error"]);
        $controller_class =  $controller_class[count($controller_class)-1];
        $params           =  array("message"=>"<b/>Error:</b> The Class: <i>{$classToLoad['class']}</i> No Found in <i>{$classToLoad['path']}</i>.");      
        $controller_class->error($params);
}