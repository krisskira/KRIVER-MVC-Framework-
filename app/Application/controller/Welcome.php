<?php
class Welcome extends Controller {

    public function index( $params=array() )
    {
        $myOrm = $this::entityLoad("welcome",["Username:=:krisskira","Password:=:123456789"],TRUE);
        //var_dump ( $myOrm->getProperties() );
    }
}