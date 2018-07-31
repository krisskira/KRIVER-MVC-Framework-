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
<style>
.ErrorTable
{
    margin: auto;
}
.ErrorTable, 
.ErrorTable tr td,
.ErrorTable tr th
    {
        border: 1px solid darkgray;
        border-collapse: collapse;
        border-spacing: 0ch;
        padding: 5px;
        font-style: italic;
    }
.ErrorTable tr th
    {
        text-align: left;
        color: white;
        background-color: cadetblue;
        font-family:  'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
        font-size: 12px;
    }
.ErrorTable tr td 
    {
        font-family: 'Courier New', Courier, monospace;
        transition: background-color 0.5s;
    }
.ErrorTable td:hover
    {
        background-color: lightblue;
        cursor: pointer;
    }
</style>

<div>
    <table class="ErrorTable">
        <tr>
            <th>Template:</th><td><?php echo $_error_server_file ;?></td>
        </tr>
        <tr>
            <th>Line:</th><td><?php echo $_error_server_line ;?></td>
        </tr>
        <tr>
            <th>Error Type:</th><td><?php echo $_error_server_type ;?></td>
        </tr>
        <tr>
            <th>Description:</th><td><?php echo $_error_server_message ;?></td>
        </tr>
    </table>
</div>