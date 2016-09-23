<?php

class systemController extends controller {

    public function __construct() {

    }

    public function index($action = '', $id = 0) {

        include(dir::view('system/general.php'));

    }

    public function advanced($action = '', $id = 0) {

        include(dir::view('system/advanced.php'));

    }

}

?>
