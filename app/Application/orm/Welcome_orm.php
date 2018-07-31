<?php

class Welcome_ORM extends ORM {
    protected static $table = "Users";

    protected static $columns = array(
        "id"        =>  "string,required",
        "Nombre"    =>  "string,required",
        "Apellido"  =>  "string,null",
        "Username"  =>  "int,null",
        "Password"  =>  "int,null"
    );

    protected static $alias_columns = array(
        "FirstName" =>  "Nombre",
        "LastName"  =>  "Apellido"
    );

}