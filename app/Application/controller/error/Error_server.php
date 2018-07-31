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
class Error_server extends Errors {
    function error($param){
        self::loadView($param);
    }
 }
?>