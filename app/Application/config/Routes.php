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
/** 
 * 
 *  (o) $route["/endpoint"] = "path/controller";                            Intancia el controllador y llama la funcion index
 *  (o) $route["/endpoint"] = "path/controller/param1/param_n";             Instancia el controlador, llama la funcion index y 
 *                                                                          le pasa un array como parametro con [param1,param_n]
 * 
 *  (o) $route["/endpoint"] = "path/controller/function";                   Instancia el controlador y llama la funcion function
 * 
 *  (o) $route["/endpoint"] = "path/controller/function/param1/param2";     Instancia el controlador, llama la funcion function y 
 *                                                                          le pasa un array como parametro con [param1,param_n] 
 * **/

$route["controller_error"]      = "error/error_server";
$route["view_error"]            = "error/error_server";
$route["view_no_found"]         = "error/404";
$route["controller_default"]    = "welcome";