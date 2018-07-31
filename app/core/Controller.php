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

abstract class Controller {
    
    abstract public function index();
    
    static function loadView( $view, $params = [] ){
        global $route;

        if(  file_exists( _DIR_VIEW_.$view.".php" ) ) {
            $prefix = explode("/",$view);
            $prefix = $prefix[count($prefix)-1];
            //print_r($prefix);
            extract($params,EXTR_PREFIX_ALL,"_{$prefix}");
            ob_start();
                @include _DIR_VIEW_.$view.".php";
            ob_end_flush();
        }
        else {
            ob_start();
                @include _DIR_VIEW_.$route["view_no_found"].".php";
            ob_end_flush();
        }
    }

    protected function entityLoad( $model, $filters=[], $isMultidimensional=TRUE ){
        $retModel = false;

        if(  file_exists( _DIR_ORM_.$model."_ORM.php" ) ){
            require_once ( _DIR_ORM_.$model."_ORM.php" );
            if ( class_exists ( $model ) ) {
                $dynModel = $model."_ORM";
                $retModel = new $dynModel();
                $retModel->getTable( $filters,  $isMultidimensional );
            }
        }
        return $retModel;
    }

    protected function entityNew($model)
    {
        return array();
    }

    protected function entitySave($model) {
        return true;
    }



}