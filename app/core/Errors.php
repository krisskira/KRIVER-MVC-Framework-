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
abstract class Errors {
    abstract function error($error);

    static function loadView( $error=array("type"=>0,"message"=>"","template"=>"","line"=>0) ) {
        global $route;
       
        switch ( $error["type"] ){
            case E_USER_ERROR:
                $error = unserialize( $error["message"] );
                break;
            default:
                $error["message"] = str_replace ("#","<br>->",$error["message"]);
                break;

        }

        $templateErroHtml = explode ("/", str_replace ( "\\" ,"/", __DIR__ ) );
        unset( $templateErroHtml[count($templateErroHtml)-1] );
        $templateErroHtml = implode( "/", $templateErroHtml );
        $templateErroHtml .= "/"._DIR_VIEW_.$route["view_error"].".php";
        extract($error,EXTR_PREFIX_ALL,"_error_server");
        ob_start();
            @include_once( $templateErroHtml );
        ob_end_flush();
    }
}